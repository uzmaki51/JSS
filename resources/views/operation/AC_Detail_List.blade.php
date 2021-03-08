<?php $index = 1; ?>
@foreach($ACDetails as $ACDetail)
<tr>
    <td class="center">{{$index++}}</td>
    <td class="hidden">{{$ACDetail['id']}}</td>
    <td class="center">{{$ACDetail['AC_Item_Cn']}}</td>
    <td>{{$ACDetail['AC_Detail_Item_Cn']}}</td>
    <td>{{$ACDetail['AC_Detail_Item_Abb']}}</td>
    <td>{{$ACDetail['AC_Detail Item_Referance']}}</td>
    <td><a class="simple_text hide-option" style="width:100px;padding-top:4px;cursor: pointer" title="{{$ACDetail['AC_Detail_Item_Description']}}">{{$ACDetail['AC_Detail_Item_Description']}}</a></td>
    <td>{{$ACDetail['Order_No']}}</td>
    <td>
        <div class="action-buttons">
            <a href="#children_item" class="blue edit_sub_type">
                <i class="icon-edit bigger-130"></i>
            </a>

            <a class="red del_sub_type">
                <i class="icon-trash bigger-130"></i>
            </a>
        </div>
    </td>
</tr>
@endforeach
*****{{$AC_Item_name}}