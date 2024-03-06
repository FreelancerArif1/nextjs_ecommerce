@foreach ($products as $row)
    
    <tr id="drag_row">
        <td><i class="mdi mdi-format-list-bulleted"></i></td>
        <td>
            <a href="#">{{ $row->title }}</a>
            <input type="hidden" name="selected_products[]" value="{{ $row->id }}">
        </td>
        <td>{{ Helper::getDefaultCurrency()->currency_symbol }} {{ $row->price }}</td>
        <td>
            <input type="number" step="" name="product_discount[]" value="{{ $row->special_price ?? '' }}"
                placeholder="Discount Amount" class="form-control">
        </td>
        <td>
            <select name="discount_type[]" class="form-control">
                <option value="1" @if ($row->special_price_type == 1) selected @endif>Fixed</option>
                <option value="2" @if ($row->special_price_type == 2) selected @endif>Percent</option>
            </select>
        </td>
        {{-- <input type="hidden" name="short_order[]" value="{{ $sort_order }}" id="sort_order"> --}}
    </tr>
@endforeach
