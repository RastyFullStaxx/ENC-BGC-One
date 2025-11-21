<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Policy;
use App\Models\PolicyRule;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class AdminPolicyController extends Controller
{
    public function index()
    {
        $policies = Policy::with('rules')
            ->orderBy('updated_at', 'desc')
            ->get();

        $policyCollection = collect($policies);
        $activeCount = $policyCollection->where('status', 'active')->count();
        $draftCount = $policyCollection->where('status', 'draft')->count();
        $primaryPolicy = $policies->first();

        $bookings = $policyCollection->where('domain_key', 'bookings')->values();
        $sfi = $policyCollection->where('domain_key', 'sfi')->values();
        $shuttle = $policyCollection->where('domain_key', 'shuttle')->values();

        return view('admin.policies', compact(
            'policies',
            'bookings',
            'sfi',
            'shuttle',
            'activeCount',
            'draftCount',
            'primaryPolicy'
        ));
    }

    public function store(Request $request)
    {
        $data = $this->validatePolicy($request);
        $policy = Policy::create($data);

        return response()->json($policy->load('rules'), 201);
    }

    public function update(Request $request, Policy $policy)
    {
        $data = $this->validatePolicy($request, $policy);
        $policy->update($data);

        return response()->json($policy->fresh('rules'));
    }

    public function destroy(Policy $policy)
    {
        $policy->delete();

        return response()->json(['deleted' => true]);
    }

    public function setStatus(Request $request, Policy $policy)
    {
        $validated = $request->validate([
            'status' => ['required', 'string', Rule::in(['active', 'draft', 'archived'])],
        ]);

        $policy->status = $validated['status'];
        $policy->active = $validated['status'] === 'active';
        $policy->save();

        return response()->json($policy->fresh('rules'));
    }

    public function storeRule(Request $request, Policy $policy)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'summary' => ['nullable', 'string'],
            'position' => ['nullable', 'integer', 'min:1'],
        ]);

        $rule = $policy->rules()->create([
            'title' => $data['title'],
            'summary' => $data['summary'] ?? '',
            'position' => $data['position'] ?? ($policy->rules()->max('position') + 1),
        ]);

        return response()->json($rule, 201);
    }

    public function updateRule(Request $request, PolicyRule $rule)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'summary' => ['nullable', 'string'],
            'position' => ['nullable', 'integer', 'min:1'],
        ]);

        $rule->update($data);

        return response()->json($rule->fresh());
    }

    public function destroyRule(PolicyRule $rule)
    {
        $rule->delete();

        return response()->json(['deleted' => true]);
    }

    private function validatePolicy(Request $request, ?Policy $policy = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'domain_key' => ['required', 'string', Rule::in(['bookings', 'sfi', 'shuttle'])],
            'status' => ['nullable', 'string', Rule::in(['active', 'draft', 'archived'])],
            'active' => ['sometimes', 'boolean'],
            'owner' => ['nullable', 'string', 'max:255'],
            'reminder' => ['nullable', 'string', 'max:255'],
            'updated_by' => ['nullable', 'string', 'max:255'],
            'desc' => ['nullable', 'string'],
            'impact' => ['nullable', 'string'],
            'tags' => ['nullable'],
            'expiring' => ['nullable', 'boolean'],
            'needs_review' => ['nullable', 'boolean'],
        ]);

        $data['status'] = $data['status'] ?? ($data['active'] ?? false ? 'active' : 'draft');
        $data['active'] = $data['status'] === 'active';
        $data['tags'] = $this->normalizeTags($data['tags'] ?? null);

        return $data;
    }

    private function normalizeTags($tags): array
    {
        if (is_null($tags)) {
            return [];
        }

        if (is_string($tags)) {
            $tags = explode(',', $tags);
        }

        return collect($tags)
            ->map(fn ($tag) => trim((string) $tag))
            ->filter()
            ->values()
            ->all();
    }
}
