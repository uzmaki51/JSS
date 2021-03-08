<table id="PlanManageTable" class="table table-bordered table-hover">
    <tbody>
    <?php $index = 1;?>
    @foreach($sub_plan_list as $plan)
        <tr>
            <td data-id="{{$plan->id}}" style="width: 5%;">{{$index++}}</td>
            <td class="center" data-main="{{$plan->mainId}}" style="width: 10%;">{{ $plan->mainPlan }}</td>
            <td class="center" style="width: 10%;">{{ $plan->planTitle }}</td>
            <td class="center" style="width: 7%;">{{ $plan->startDate }} </td>
            <td class="center" style="width: 7%;">{{ $plan->endDate }} </td>
            <td class="left" style="width: 50%;">{{ $plan->descript }} </td>
            <td class="center" data-color="{{$plan->color}}" style="width: 3%;">
                <div style="width:4px;height:0;border:5px solid #{{ $plan->color }};overflow:hidden;" ></div>
            </td>
            <td class="center control action-buttons" style="width: 5%;">
                <a class="plan-edit-btn" href="javascript:void(0);">
                    <i class="blue icon-edit bigger-130"></i>
                </a>
                <a class="plan-del-btn" href="javascript:void(0);">
                    <i class="red icon-trash bigger-130"></i>
                </a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>