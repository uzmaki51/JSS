<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header no-padding">
            <div class="table-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <span class="white">&times;</span>
                </button>
                Sail Distance Table
            </div>
        </div>
        <div class="modal-body no-padding">
            <table class="table table-striped table-bordered table-hover no-margin-bottom no-border-top bunker-table">
                <thead>
                <tr class="black br-hblue">
                    <th style="width:50px"></th>
                    <th>Status</th>
                    <th>Position</th>
                    <th>Qtty</th>
                    <th>Distance</th>
                    <th>Fo[MT]</th>
                    <th>Do[MT]</th>
                    <th>Co[KG]</th>
                    <th>Ao[KG]</th>
                </tr>
                </thead>
                <tbody>
                @foreach($list as $log)
                    <tr>
                        <td><input type="checkbox"></td>
                        <td class="center">{{$log['voyStatus']['Voy_Status']}}</td>
                        <td class="center">{{$log['Ship_Position']}}</td>
                        <td class="center">{{$log['Cargo_Qtty']}}</td>
                        <td class="center">{{$log['Sail_Distance']}}</td>
                        <td class="center">{{$log['ROB_FO']}}</td>
                        <td class="center">{{$log['ROB_DO']}}</td>
                        <td class="center">{{$log['ROB_LO_M']}}</td>
                        <td class="center">{{$log['ROB_LO_A']}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="modal-footer no-margin-top">
            <button class="btn btn-sm btn-danger pull-left btn-confirm" data-dismiss="modal">
                <i class="icon-check"></i>
                확 인
            </button>
        </div>
    </div><!-- /.modal-content -->
</div>

<script>
    $(function () {
        bindBtnClickEvent();
    });
</script>