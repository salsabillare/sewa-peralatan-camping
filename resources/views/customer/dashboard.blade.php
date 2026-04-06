@auth
@php $notifications = auth()->user()->unreadNotifications; @endphp
<div>
    @foreach($notifications as $notification)
        <div style="background:#e0d4ff; color:#4b0082; padding:10px; border-radius:8px; margin-bottom:5px;">
            {{ $notification->data['message'] }}
            <a href="{{ route('customer.orders.show', $notification->data['order_id']) }}">Lihat</a>
        </div>
    @endforeach
</div>
@endauth
