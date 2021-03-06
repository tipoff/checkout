<div {{ $attributes }}>
    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th>Qty</th>
                <th>Each</th>
                <th>Discount</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($cart->getItems() as $item)
            <x-dynamic-component :component="$getItemComponent($item)" :cart-item="$item" :sellable="$item->getSellable()"/>
        @endforeach
        </tbody>
    </table>
</div>
