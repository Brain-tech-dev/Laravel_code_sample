@php
    $i = $logs->firstItem();
@endphp
<div class="table-responsive mt-3">
    <table class="table align-middle" style="background-color:#8e8e8">
        <thead class="table-secondary">
            <tr>
                <td>#</td>
                {{-- <td>Admin ID</td> --}}
                <td class="no-wrap ">Username</td>
                <td class="no-wrap ">Lead ID</td>
                <td class="no-wrap ">Field</td>
                <td class="text-wrap ">Old Value</td>
                <td class="text-wrap ">New Value</td>
                <td class="text-wrap ">Messages</td>
                <td class="no-wrap ">Created At</td>
            </tr>
        </thead>
        <tbody>
            @if($logs && !empty($logs))
                @forelse ($logs as $key => $item)
                    <tr class="{{($item->is_test == 1 && admin()->user()->role_id == 'superadmin') ? 'table-warning' : ''}}">
                        <td>{{ $i }}</td>
                        {{-- <td>{{ $item->admin_id }}</td> --}}
                        <td>{{ $item->user_name }}</td>
                        <td class="no-wrap">{{ $item->lead_show_id }}</td>
                        <td class="no-wrap">{{ $item->page }}</td>
                        <td class="text-wrap" style="width:20%;">{{ $item->old_status }}</td>
                        <td class="text-wrap" style="width:20%;">{{ $item->new_status }}</td>
                        <td class="text-wrap" style="width:20%;">{{ $item->messages }}</td>
                        <td class="no-wrap">{{ date('d M Y',strtotime($item->created_at)) }}</td>
                    </tr>
                    @php
                        $i++;
                    @endphp
                @empty
                    <tr>
                        <td colspan="10" class="text-center">
                            No Record Found !
                        </td>
                    </tr>
                @endforelse
            @endif
        </tbody>
    </table>
</div>

{{-- pagination with filter --}}
@include('admin.elements.filter-with-pagi', ['data' => $logs])