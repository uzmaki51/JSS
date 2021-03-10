<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header no-padding">
            <div class="table-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <span class="white">&times;</span>
                </button>
                Daily bunker consumption and price
            </div>
        </div>
        <div class="modal-body no-padding">
            <table class="table table-striped table-bordered table-hover no-margin-bottom no-border-top consum-table">
                <thead>
                    <tr class="black br-hblue">
                        <th style="width:50px"></th>
                        <th>Condition</th>
                        <th>Fuel/Unit</th>
                        <th>Sail Cons</th>
                        <th>L/D Cons</th>
                        <th>Idle Cons</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="summer_fo">
                        <td rowspan="3"><input type="checkbox" class="summer"></td>
                        <td class="center" rowspan="3">Summer</td>
                        <td class="center">FO[MT/DAY]</td>
                        <td class="center">{{$info['FOSailCons_S']}}</td>
                        <td class="center">{{$info['FOL/DCons_S']}}</td>
                        <td class="center">{{$info['FOIdleCons_S']}}</td>
                    </tr>
                    <tr class="summer_do">
                        <td class="center">DO[MT/DAY]</td>
                        <td class="center">{{$info['DOSailCons_S']}}</td>
                        <td class="center">{{$info['DOL/DCons_S']}}</td>
                        <td class="center">{{$info['DOIdleCons_S']}}</td>
                    </tr>
                    <tr class="summer_lo">
                        <td class="center">LO[MT/DAY]</td>
                        <td class="center">{{$info['LOSailCons_S']}}</td>
                        <td class="center">{{$info['LOL/DCons_S']}}</td>
                        <td class="center">{{$info['LOIdleCons_S']}}</td>
                    </tr>
                    <tr class="winter_fo">
                        <td rowspan="3"><input type="checkbox" class="winter"></td>
                        <td class="center" rowspan="3">Winter</td>
                        <td class="center">FO[MT/DAY]</td>
                        <td class="center">{{$info['FOSailCons_W']}}</td>
                        <td class="center">{{$info['FOL/DCons_W']}}</td>
                        <td class="center">{{$info['FOIdleCons_W']}}</td>
                    </tr>
                    <tr class="winter_do">
                        <td class="center">DO[MT/DAY]</td>
                        <td class="center">{{$info['DOSailCons_W']}}</td>
                        <td class="center">{{$info['DOL/DCons_W']}}</td>
                        <td class="center">{{$info['DOIdleCons_W']}}</td>
                    </tr>
                    <tr class="winter_lo">
                        <td class="center">LO[MT/DAY]</td>
                        <td class="center">{{$info['LOSailCons_W']}}</td>
                        <td class="center">{{$info['LOL/DCons_W']}}</td>
                        <td class="center">{{$info['LOIdleCons_W']}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="modal-footer no-margin-top">
            <button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">
                <i class="icon-check"></i>
                确认
            </button>
        </div>
    </div><!-- /.modal-content -->
</div>

<script>
    $(function () {
        bindBtnClickEvent();
    });
</script>