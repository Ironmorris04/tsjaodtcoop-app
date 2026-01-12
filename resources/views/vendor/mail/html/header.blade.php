@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel' || trim($slot) === 'TSJAODT Cooperative')
<div style="font-size: 24px; font-weight: bold; color: #4e73df; font-family: Arial, sans-serif;">
    TSJAODT Cooperative
</div>
@else
{!! $slot !!}
@endif
</a>
</td>
</tr>
