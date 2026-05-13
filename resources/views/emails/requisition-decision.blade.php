<x-mail::message>
@if($decision === 'approved')
# Your Requisition Has Been Approved ✓

Good news! Your requisition has been reviewed and **approved**.
@else
# Your Requisition Requires Revision

Your requisition has been reviewed and **sent back for revision**. Please see the reviewer's notes below and resubmit once addressed.
@endif

<x-mail::panel>
**{{ $requisition->title }}**

| Field | Details |
|---|---|
| Type | {{ \App\Models\Requisition::$types[$requisition->requisition_type] ?? $requisition->requisition_type }} |
@if($requisition->amount)
| Amount | {{ $requisition->currency }} {{ number_format($requisition->amount, 2) }} |
@endif
| Decision By | {{ $reviewer->name }} |
| Decision Date | {{ $requisition->reviewed_at?->format('d M Y, H:i') }} |
</x-mail::panel>

@if($requisition->review_notes)
**{{ $decision === 'approved' ? 'Reviewer Note' : 'Reason / Action Required' }}:**
{{ $requisition->review_notes }}
@endif

@if($decision === 'rejected')
You may edit your requisition to address the feedback and resubmit it for approval.
@endif

<x-mail::button :url="$url" color="{{ $decision === 'approved' ? 'success' : 'primary' }}">
View Requisition
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
