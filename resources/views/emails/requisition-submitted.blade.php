<x-mail::message>
# New Requisition Awaiting Your Approval

A new requisition has been submitted and requires your review.

<x-mail::panel>
**{{ $requisition->title }}**

| Field | Details |
|---|---|
| Submitted By | {{ $submitter->name }} ({{ $submitter->email }}) |
| Type | {{ \App\Models\Requisition::$types[$requisition->requisition_type] ?? $requisition->requisition_type }} |
| Priority | {{ ucfirst($requisition->priority) }} |
@if($requisition->amount)
| Amount Requested | {{ $requisition->currency }} {{ number_format($requisition->amount, 2) }} |
@endif
@if($requisition->needed_by_date)
| Needed By | {{ $requisition->needed_by_date->format('d M Y') }} |
@endif
| Submitted | {{ $requisition->submitted_at?->format('d M Y, H:i') }} |
</x-mail::panel>

**Description:**
{{ $requisition->description }}

@if($requisition->justification)
**Justification:**
{{ $requisition->justification }}
@endif

Please log in to review and take action on this requisition.

<x-mail::button :url="$url" color="primary">
Review Requisition
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
