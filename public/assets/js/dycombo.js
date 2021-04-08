
        for (const selector of document.querySelectorAll(".dynamic-select-wrapper")) {
            selector.addEventListener('click', function() {
                this.firstElementChild.classList.toggle('open');
            })
        }

        addCustomEvent();

        function openDynamicPopup(type) {
            $('#dynamic-type').val(type);
            
            var url;
            if (type == 'nationality') {
                url = BASE_URL + 'ajax/getNationality';
            }

            $.ajax({
                url: BASE_URL + 'ajax/getDynamicData',
                type: 'post',
                data: {
                    type: type
                },
                success: function(data, status, xhr) {
                    $('#dynamic-data').val('');
                    $('#dynamic-default').html('');
                    for (var i = 0; i < data.length; i ++) {
                        $('#dynamic-data').val($('#dynamic-data').val() + data[i].name + "\n");
                        if (data[i].isDefault)
                            $('#dynamic-default').html($('#dynamic-default').html() + '<option selected value="' + i + '">' + data[i].name + '</option>');
                        else
                            $('#dynamic-default').html($('#dynamic-default').html() + '<option value="' + i + '">' + data[i].name + '</option>');
                    }
                    $('#modal-dynamic').modal('show');
                },
                error: function(error, status) {
                    alert(error);
                }
            });
        }

        $('#dynamic-data').change(function () {
            var data = $('#dynamic-data').val();
            var str = data.replace(/(?:\r\n|\r|\n)/g, ',');
            var list = str.split(',');
            $('#dynamic-default').html('');
            for (var i=0;i<list.length;i++)
                $('#dynamic-default').html($('#dynamic-default').html() + '<option value="' + i + '">' + list[i] + '</option>');
        });

        function dynamicSubmit() {
            var type = $('#dynamic-type').val();
            var data = $('#dynamic-data').val();
            var def = $('#dynamic-default').val();
            var str = data.replace(/(?:\r\n|\r|\n)/g, ',');
            var list = str.split(',');

            setDynamicData(type, list, def);
        }

        function updatePage(element, list, def)
        {
        }

        function setDynamicData(type, list, def) {
            $("#modal-dynamic").modal("hide");
            $.ajax({
                url: BASE_URL + 'ajax/setDynamicData', 
                type: 'post',
                data: {
                    list: list,
                    type: type,
                    default: def,
                },
                success: function(data, status, xhr) {
                    if (data != '-1') {
                        alert("Success!");
                        var type = $('#dynamic-type').val();
                        var id='';
                        if (type == 'nationality') {
                            id = 'Nationality';
                        }
                        var dest = $('input[name="' + id + '"]').closest('.dynamic-select');
                        dest.find('.dynamic-select__trigger span').text(list[def]);
                        dest.children(":first").val(list[def]);
                        dest = dest.find('.dynamic-options-scroll');
                        dest.html('');
                        for (var i=0;i<list.length;i++)
                            if (i == def)
                                dest.html(dest.html() + '<span class="dynamic-option selected" data-value="' + list[i] + '">' + list[i] + '</span>');
                            else
                                dest.html(dest.html() + '<span class="dynamic-option" data-value="' + list[i] + '">' + list[i] + '</span>');
                        
                        addCustomEvent();
                    }
                },
                error: function(error, status) {
                    alert("Failed!");
                }
            })
        }

        function getDynamicData(type) {
            $.ajax({
                url: BASE_URL + 'ajax/getDynamicData',
                type: 'post',
                data: {
                    type : type
                },
                success: function(data, status, xhr) {
                    console.log(data);
                },
                error: function(error, status) {
                    console.log(data);
                }
            });
        }

        ///////////////////////////////////////////////////////////////////
        /// SHIP TYPE LIST DYNAMIC LIST
        ///////////////////////////////////////////////////////////////////
        function openShipTypeList(type) {
            $.ajax({
                url: BASE_URL + 'ajax/getDynamicData',
                type: 'post',
                data: {
                    type: type
                },
                success: function(data, status, xhr) {
                    $('#shiptype-table').html('');
                    for (var i = 0; i < data.length; i ++) {
                        var row = '<tr class="rank-tr"><td class="no-padding center"><input type="text" onfocus="addShipType(this)" class="form-control" name="ShipType_OrderNo[]"value="';
                        row += (data[i].OrderNo != null) ? data[i].OrderNo : '';
                        row += '" style="width: 100%;text-align: center"></td><td class="no-padding"><input type="text" onfocus="addShipType(this)" class="form-control" name="ShipType_Name[]"value="';
                        row += (data[i].ShipType != null) ? data[i].ShipType : '';
                        row += '" style="width: 100%;text-align: center"></td><td class="no-padding center"><div class="action-buttons"><a class="red" onClick="javascript:deleteShipType(this)"><i class="icon-trash"></i></a></div></td></tr>';
                        $('#shiptype-table').append(row);
                    }
                    addRank(null);
                    $('#modal-shiptype-list').modal('show');
                },
                error: function(error, status) {
                    alert(error);
                }
            });
        }

        function dynamicShipTypeSubmit(type) {
            var list = [];
            if (type == 'shiptype') {
                list['orderno'] = $("input[name='ShipType_OrderNo[]']").map(function(){return $(this).val();}).get();
                list['name'] = $("input[name='ShipType_Name[]']").map(function(){return $(this).val();}).get();
            }

            if (confirm('Are you sure want to save?')) {
                $("#modal-shiptype-list").modal("hide");
                $.ajax({
                    url: BASE_URL + 'ajax/setDynamicData', 
                    type: 'post',
                    data: {
                        orderno: list['orderno'],
                        name: list['name'],
                        type: type,
                    },
                    success: function(data, status, xhr) {
                        if (data != '-1') {
                            var def = 0;
                            var id='';
                            if (type == 'shiptype') {
                                id = 'ShipType';
                            }
                            var dest = $('input[name="' + id + '"]').closest('.dynamic-select');
                            dest.find('.dynamic-select__trigger span').text(list['name'][def]);
                            dest.children(":first").val(def);
                            dest = dest.find('.dynamic-options-scroll');
                            dest.html('');
                            for (var i=0;i<list['name'].length;i++)
                                if (i == def)
                                    dest.html(dest.html() + '<span class="dynamic-option selected" data-value="' + i + '">' + list['name'][i] + '</span>');
                                else
                                    dest.html(dest.html() + '<span class="dynamic-option" data-value="' + i + '">' + list['name'][i] + '</span>');
                            
                            addCustomEvent();
                            alert("Success!");
                        }
                    },
                    error: function(error, status) {
                        alert("Failed!");
                    }
                })
            }
        }

        function deleteShipType(e)
        {
            if ($('#shiptype-table tr').length > 2) { // && !$(e).closest("tr").is(":last-child")) {
                if (confirm("Are you sure to delete?")) {
                    console.log($(e).closest("tr"));
                    $(e).closest("tr").remove();
                    for (var i=0;i<$('#shiptype-table').children().length;i++) {
                        $($('#shiptype-table').children()[i].firstChild.firstChild).val(i+1);
                    }
                }
            }
        }

        function addShipType(e)
        {
            if ($('#shiptype-table tr').length > 0)
            {
                if (e == null || $(e).closest("tr").is(":last-child")) {
                    $("#shiptype-table").append('<tr class="rank-tr"><td class="no-padding center"><input type="text" onfocus="addShipType(this)" class="form-control" name="ShipType_OrderNo[]"value="" style="width: 100%;text-align: center"></td><td class="no-padding"><input type="text" onfocus="addShipType(this)" class="form-control" name="ShipType_Name[]"value="" style="width: 100%;text-align: center"></td><td class="no-padding center"><div class="action-buttons"><a class="red" onClick="javascript:deleteShipType(this)"><i class="icon-trash"></i></a></div></td></tr>');
                }
            }
        }

        ///////////////////////////////////////////////////////////////////
        /// RANK LIST DYNAMIC LIST
        ///////////////////////////////////////////////////////////////////
        function openRankList(type) {
            $.ajax({
                url: BASE_URL + 'ajax/getDynamicData',
                type: 'post',
                data: {
                    type: type
                },
                success: function(data, status, xhr) {
                    $('#rank-table').html('');
                    for (var i = 0; i < data.length; i ++) {
                        var row = '<tr class="rank-tr"><td class="no-padding center"><input type="text" onfocus="addRank(this)" class="form-control" name="Rank_OrderNo[]"value="';
                        row += (data[i].OrderNo != null) ? data[i].OrderNo : '';
                        row += '" style="width: 100%;text-align: center"></td><td class="no-padding"><input type="text" onfocus="addRank(this)" class="form-control" name="Rank_Name[]"value="';
                        row += (data[i].Duty_En != null) ? data[i].Duty_En : '';
                        row += '" style="width: 100%;text-align: center"></td><td class="no-padding center"><input type="text" onfocus="addRank(this)" class="form-control" name="Rank_Abb[]"value="';
                        row += (data[i].Abb != null) ? data[i].Abb : '';
                        row += '" style="width: 100%;text-align: center"></td><td class="no-padding"><input type="text" onfocus="addRank(this)" class="form-control" name="Rank_Description[]"value="';
                        row += (data[i].Description != null) ? data[i].Description : '';
                        row += '" style="width: 100%;text-align: center"></td><td class="no-padding center"><div class="action-buttons"><a class="red" onClick="javascript:deleteRank(this)"><i class="icon-trash"></i></a></div></td></tr>';
                        $('#rank-table').append(row);
                    }
                    addRank(null);
                    $('#modal-rank-list').modal('show');
                },
                error: function(error, status) {
                    alert(error);
                }
            });
        }
        
        function dynamicRankSubmit(type) {
            var list = [];
            if (type == 'rank') {
                list['orderno'] = $("input[name='Rank_OrderNo[]']").map(function(){return $(this).val();}).get();
                list['name'] = $("input[name='Rank_Name[]']").map(function(){return $(this).val();}).get();
                list['abb'] = $("input[name='Rank_Abb[]']").map(function(){return $(this).val();}).get();
                list['description'] = $("input[name='Rank_Description[]']").map(function(){return $(this).val();}).get();
            }

            if (confirm('Are you sure want to save?')) {
                $("#modal-rank-list").modal("hide");
                $.ajax({
                    url: BASE_URL + 'ajax/setDynamicData', 
                    type: 'post',
                    data: {
                        orderno: list['orderno'],
                        name: list['name'],
                        abb: list['abb'],
                        description: list['description'],
                        type: type,
                    },
                    success: function(data, status, xhr) {
                        if (data != '-1') {
                            var def = 0;
                            var id='';
                            if (type == 'rank') {
                                id = 'DutyID_Book';
                            }
                            var dest = $('input[name="' + id + '"]').closest('.dynamic-select');
                            dest.find('.dynamic-select__trigger span').text(list['name'][def]);
                            dest.children(":first").val(def);
                            dest = dest.find('.dynamic-options-scroll');
                            dest.html('');
                            for (var i=0;i<list['name'].length;i++)
                                if (i == def)
                                    dest.html(dest.html() + '<span class="dynamic-option selected" data-value="' + i + '">' + list['name'][i] + '</span>');
                                else
                                    dest.html(dest.html() + '<span class="dynamic-option" data-value="' + i + '">' + list['name'][i] + '</span>');
                            
                            addCustomEvent();
                            alert("Success!");
                        }
                    },
                    error: function(error, status) {
                        alert("Failed!");
                    }
                })
            }
        }

        function deleteRank(e)
        {
            if ($('#rank-table tr').length > 2) { // && !$(e).closest("tr").is(":last-child")) {
                if (confirm("Are you sure to delete?")) {
                    console.log($(e).closest("tr"));
                    $(e).closest("tr").remove();
                    for (var i=0;i<$('#rank-table').children().length;i++) {
                        $($('#rank-table').children()[i].firstChild.firstChild).val(i+1);
                    }
                }
            }
        }

        function addRank(e)
        {
            if ($('#rank-table tr').length > 0)
            {
                if (e == null || $(e).closest("tr").is(":last-child")) {
                    $("#rank-table").append('<tr class="rank-tr"><td class="no-padding center"><input type="text" onfocus="addRank(this)" class="form-control" name="Rank_OrderNo[]"value="" style="width: 100%;text-align: center"></td><td class="no-padding"><input type="text" onfocus="addRank(this)" class="form-control" name="Rank_Name[]"value="" style="width: 100%;text-align: center"></td><td class="no-padding center"><input type="text" onfocus="addRank(this)" class="form-control" name="Rank_Abb[]"value="" style="width: 100%;text-align: center"></td><td class="no-padding"><input type="text" onfocus="addRank(this)" class="form-control" name="Rank_Description[]"value="" style="width: 100%;text-align: center"></td><td class="no-padding center"><div class="action-buttons"><a class="red" onClick="javascript:deleteRank(this)"><i class="icon-trash"></i></a></div></td></tr>');
                }
            }
        }

        ///////////////////////////////////////////////////////////////////
        /// CAPACITY LIST DYNAMIC LIST
        ///////////////////////////////////////////////////////////////////
        function openCapacityList(type) {
            $.ajax({
                url: BASE_URL + 'ajax/getDynamicData',
                type: 'post',
                data: {
                    type: type
                },
                success: function(data, status, xhr) {
                    $('#capacity-table').html('');
                    for (var i = 0; i < data.length; i ++) {
                        var row = '<tr class="rank-tr"><td class="no-padding center">';
                        row += (i + 1);
                        row += '</td><td class="no-padding"><input type="text" onfocus="addCapacity(this)" class="form-control" name="Capacity_Name[]"value="';
                        row += (data[i].Capacity_En != null) ? data[i].Capacity_En : '';
                        row += '" style="width: 100%;text-align: center"></td><td class="no-padding center"><input type="text" onfocus="addCapacity(this)" class="form-control" name="Capacity_STCW[]"value="';
                        row += (data[i].STCWRegCode != null) ? data[i].STCWRegCode : '';
                        row += '" style="width: 100%;text-align: center"></td><td class="no-padding"><input type="text" onfocus="addCapacity(this)" class="form-control" name="Capacity_Description[]"value="';
                        row += (data[i].Description != null) ? data[i].Description : '';
                        row += '" style="width: 100%;text-align: center"></td><td class="no-padding center"><div class="action-buttons"><a class="red" onClick="javascript:deleteCapacity(this)"><i class="icon-trash"></i></a></div></td></tr>';
                        $('#capacity-table').append(row);
                    }
                    addCapacity(null);
                    $('#modal-capacity-list').modal('show');
                },
                error: function(error, status) {
                    alert(error);
                }
            });
        }
        
        function dynamicCapacitySubmit(type) {
            var list = [];
            if (type == 'capacity') {
                list['name'] = $("input[name='Capacity_Name[]']").map(function(){return $(this).val();}).get();
                list['STCW'] = $("input[name='Capacity_STCW[]']").map(function(){return $(this).val();}).get();
                list['description'] = $("input[name='Capacity_Description[]']").map(function(){return $(this).val();}).get();
            }

            if (confirm('Are you sure want to save?')) {
                $("#modal-capacity-list").modal("hide");
                $.ajax({
                    url: BASE_URL + 'ajax/setDynamicData', 
                    type: 'post',
                    data: {
                        name: list['name'],
                        STCW: list['STCW'],
                        description: list['description'],
                        type: type,
                    },
                    success: function(data, status, xhr) {
                        if (data != '-1') {
                            var def = 0;
                            var id='';
                            var id2='';
                            if (type == 'rank') {
                                id = 'CapacityID';
                                id2 = 'COEId';
                            }
                            var dest = $('input[name="' + id + '"]').closest('.dynamic-select');
                            dest.find('.dynamic-select__trigger span').text(list['name'][def]);
                            dest.children(":first").val(def);
                            dest = dest.find('.dynamic-options-scroll');
                            dest.html('');
                            for (var i=0;i<list['name'].length;i++)
                                if (i == def)
                                    dest.html(dest.html() + '<span class="dynamic-option selected" data-value="' + i + '">' + list['name'][i] + '</span>');
                                else
                                    dest.html(dest.html() + '<span class="dynamic-option" data-value="' + i + '">' + list['name'][i] + '</span>');

                            var dest2 = $('input[name="' + id2 + '"]').closest('.dynamic-select');
                            dest2.find('.dynamic-select__trigger span').text(list['name'][def]);
                            dest2.children(":first").val(def);
                            dest2 = dest2.find('.dynamic-options-scroll');
                            dest2.html('');
                            for (var i=0;i<list['name'].length;i++)
                                if (i == def)
                                    dest2.html(dest2.html() + '<span class="dynamic-option selected" data-value="' + i + '">' + list['name'][i] + '</span>');
                                else
                                    dest2.html(dest2.html() + '<span class="dynamic-option" data-value="' + i + '">' + list['name'][i] + '</span>');
                            
                            
                            alert("Success!");
                        }
                    },
                    error: function(error, status) {
                        alert("Failed!");
                    }
                })
            }
        }

        function deleteCapacity(e)
        {
            if ($('#capacity-table tr').length > 2) { // && !$(e).closest("tr").is(":last-child")) {
                if (confirm("Are you sure to delete?")) {
                    console.log($(e).closest("tr"));
                    $(e).closest("tr").remove();
                    for (var i=0;i<$('#capacity-table').children().length;i++) {
                        $($('#capacity-table').children()[i].firstChild.firstChild).val(i+1);
                    }
                }
            }
        }

        function addCapacity(e)
        {
            if ($('#capacity-table tr').length > 0)
            {
                if (e == null || $(e).closest("tr").is(":last-child")) {
                    $("#capacity-table").append('<tr class="rank-tr"><td class="no-padding center">' + ($('#capacity-table tr').length + 1) + '<td class="no-padding"><input type="text" onfocus="addCapacity(this)" class="form-control" name="Capacity_Name[]"value="" style="width: 100%;text-align: center"></td><td class="no-padding center"><input type="text" onfocus="addCapacity(this)" class="form-control" name="Capacity_STCW[]"value="" style="width: 100%;text-align: center"></td><td class="no-padding"><input type="text" onfocus="addCapacity(this)" class="form-control" name="Capacity_Description[]"value="" style="width: 100%;text-align: center"></td><td class="no-padding center"><div class="action-buttons"><a class="red" onClick="javascript:deleteCapacity(this)"><i class="icon-trash"></i></a></div></td></tr>');
                }
            }
        }

        function addCustomEvent()
        {
            for (const option of document.querySelectorAll(".dynamic-option")) {
                option.addEventListener('click', function() {
                    if (!this.classList.contains('selected')) {
                        if (this.parentNode.querySelector('.dynamic-option.selected') != null) {
                            this.parentNode.querySelector('.dynamic-option.selected').classList.remove('selected');
                        }

                        this.classList.add('selected');
                        this.closest('.dynamic-select').querySelector('.dynamic-select__trigger span').textContent = this.textContent;
                        this.closest('.dynamic-select').firstElementChild.value = this.getAttribute('data-value');
                    }
                })
            }
        }