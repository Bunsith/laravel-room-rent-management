<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('customers.view');

        $search = $request->string('search')->toString();

        $customers = Customer::with(['phones', 'document'])
            ->when($search, function ($query) use ($search) {
                $query->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhereHas('phones', function ($sub) use ($search) {
                        $sub->where('phone', 'like', "%{$search}%");
                    });
            })
            ->orderBy('first_name')
            ->paginate(10)
            ->withQueryString();

        return view('customers.index', [
            'customers' => $customers,
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        Gate::authorize('customers.manage');

        return view('customers.create', [
            'customer' => new Customer(),
            'countries' => $this->countries(),
        ]);
    }

    public function store(CustomerRequest $request): RedirectResponse
    {
        Gate::authorize('customers.manage');

        $payload = $request->validated();

        if ($request->hasFile('photo')) {
            $payload['photo'] = $request->file('photo')->store('customers', 'public');
        }

        $customer = Customer::create($payload);

        $this->syncPhones($customer, $payload['phones'] ?? []);
        $this->syncDocuments($customer, $payload, $request);

        return redirect()->route('customers.index')->with('status', 'Customer created successfully.');
    }

    public function edit(Customer $customer): View
    {
        Gate::authorize('customers.manage');

        return view('customers.edit', [
            'customer' => $customer->load(['phones', 'document']),
            'countries' => $this->countries(),
        ]);
    }

    public function update(CustomerRequest $request, Customer $customer): RedirectResponse
    {
        Gate::authorize('customers.manage');

        $payload = $request->validated();

        if ($request->hasFile('photo')) {
            if ($customer->photo) {
                Storage::disk('public')->delete($customer->photo);
            }
            $payload['photo'] = $request->file('photo')->store('customers', 'public');
        }

        $customer->update($payload);

        $this->syncPhones($customer, $payload['phones'] ?? []);
        $this->syncDocuments($customer, $payload, $request);

        return redirect()->route('customers.index')->with('status', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        Gate::authorize('customers.manage');

        $customer->delete();

        return redirect()->route('customers.index')->with('status', 'Customer deleted successfully.');
    }

    private function syncPhones(Customer $customer, array $phones): void
    {
        $customer->phones()->delete();

        foreach ($phones as $phone) {
            if (!blank($phone)) {
                $customer->phones()->create(['phone' => $phone]);
            }
        }
    }

    private function syncDocuments(Customer $customer, array $payload, Request $request): void
    {
        $attachments = $customer->document?->attachment_file ?? [];

        if ($request->hasFile('attachments')) {
            $attachments = [];
            foreach ($request->file('attachments') as $file) {
                $attachments[] = $file->store('documents', 'public');
            }
        }

        $customer->document()->updateOrCreate(
            ['customer_id' => $customer->id],
            [
                'national_id' => $payload['national_id'] ?? null,
                'national_valid_until' => $payload['national_valid_until'] ?? null,
                'passport_id' => $payload['passport_id'] ?? null,
                'passport_valid_until' => $payload['passport_valid_until'] ?? null,
                'visa_id' => $payload['visa_id'] ?? null,
                'visa_valid_until' => $payload['visa_valid_until'] ?? null,
                'attachment_file' => $attachments ?: null,
            ]
        );
    }

    private function countries(): array
    {
        return config('countries', []);
    }
}
