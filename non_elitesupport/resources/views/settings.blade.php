@extends("layouts.masterlayout")
@section('title','Settings')
@section('bodycontent')
	<div class="content-wrapper">
        <div class="card">
            <div class="card-body">
                <h2>Settings</h2>
                <div class="row">
                    <div class="col-md-12">
						<h4 onclick="fileSetting()" style="cursor: pointer;text-decoration: underline;color: #0055ff;">File Settings</h4>
						<div id="filesettings" style="display: none">
						<form name="myForm" method="post" enctype="multipart/form-data" action="{{url('store-file-settings')}}"  onsubmit="return fileSettingValidation()">
	                        <input type="hidden" name="_token" value="{{csrf_token()}}">
							<input type="hidden" name="dataid" id="dataid"> 
                            <div class="row">                                
                                <div class="form-group col-md-3">
                                    <label for="Name">Function Type</label>
                                    <input type="text" name="function_type" id="function_type" class="form-control">
                                    <span id="function_type_error" style="color:red"></span> 
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="Name">Size</label>
                                    <input type="number" name="size" id="size" class="form-control">
                                    <span id="size_error" style="color:red"></span> 
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="Name">File Format</label>
                                    <input type="text" name="file_format" id="file_format" class="form-control">
                                    <span>Seperated, by commas</span>
                                    <span id="file_format_error" style="color:red"></span> 
                                </div>                                                                 
                            </div>
                            <div class="box-footer">
                                <span class="pull-right">	
                                <input type="submit"name="submit" id="submit" value="Submit" class="btn btn-secondary">
                                </span>
                            </div>
                        </form>     
                        <div class="clear"></div>
                        <hr>                       
                        <div class="table-responsive">
                            <table id="order-listing" class="table">
                                <thead>
                                    <tr>
										<th >S no.</th>
										<th>Function Type</th> 
										<th>Size</th>                                       
										<th>File Format</th>                                       
										<th style="text-align: right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @isset($rowData)
                                @php $count=1; @endphp							
									@foreach($rowData as $row)
                                    <tr>
                                        <td>{{$count}}</td>
                                        <td class="cls_function_type">{{$row->function_type}}</td>                                                                   
                                        <td class="cls_size">{{$row->size}}</td>                                                                   
                                        <td class="cls_file_format">{{$row->file_format}}</td>                                                                   
                                                                                                          
                                        <td style="text-align: right">
                                        <i class="fa fa-pencil-square-o" aria-hidden="true" id="{{$row->id}}" data-position="left" data-tooltip="Edit" onclick="javascript:return EditFileSettings(this);" style="cursor: pointer;"></i> <a href="{{route('file_setting_delete.fileSettingDelete', ['id' => $row->id])}}" onclick="return confirm('Do you want to delete?')"> <i class="fa fa-trash-o" aria-hidden="true" style="cursor: pointer;"></i></a>
                                        </td>
                                    </tr>
                                     @php $count++; @endphp	
                                    @endforeach
                                @endisset
                                    
                                </tbody>
                            </table>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
   
<script>
	function fileSetting(){
		$('#filesettings').show();
	}
	function EditFileSettings(el)
	{
		$('#function_type').val($(el).parents('td').parents('tr').find('.cls_function_type').text());
		$('#size').val($(el).parents('td').parents('tr').find('.cls_size').text());
		$('#file_format').val($(el).parents('td').parents('tr').find('.cls_file_format').text());
		$('#dataid').val(el.id);
	}
</script>
@endsection
