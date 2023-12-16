@extends("layouts.masterlayout")
@section('title','Zone')
@section('bodycontent')
	<div class="content-wrapper">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Manage Zone</h4>
                <div class="row">
                    <div class="col-md-12">
                    	
						<form name="myForm" method="post" enctype="multipart/form-data" action="{{url('store-zone')}}">
                        	<input type="hidden" name="_token" value="{{csrf_token()}}">
                            <input type="hidden" name="DataID" id="DataID">
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="region">Zone</label> <span style="color: red;">*</span> 
                                    <input type="text" name="region" id="region" class="form-control" placeholder="Zone" required>
                                </div>
                            </div>
                            @if(Auth::user()->role == '29' || Auth::user()->role == '30')
                            <div class="box-footer">
                                <span class="pull-right">
									<button type="button" onclick="reloadPage();" class="btn-secondary">Cancel</button>	
                                <input type="submit"name="submit" id="submit" value="Submit" class="btn-secondary">
                                </span>
                            </div>
                            @endif
                        </form>  
						
                        <div class="clear"></div>
                        <hr>
                        <div class="table-responsive">
                            <table id="order-listing" class="table">
                                <thead>
                                    <tr>
										<th>Actions</th>
										<th>Zone</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @isset($rowData)
                                @php $count=1; @endphp
									@foreach($rowData as $row)
                                    <tr>
										<td>
											<i class="fa fa-pencil-square-o" aria-hidden="true" id="{{$row->id}}" data-position="left" data-tooltip="Edit" onclick="javascript:return editZone(this);" style="cursor: pointer;"></i>
											<a href="{{route('zone_delete.zoneDelete', ['id' => $row->id])}}" onclick="return confirm('Do you want to delete?')">
												<i class="fa fa-trash-o" aria-hidden="true" style="cursor: pointer;"></i></a>
										</td>
                                        <td class="cls_region">{{$row->region}}</td>
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
   <script>
       function editZone(el){
        var region = $(el).parents('td').parents('tr').find('.cls_region').text();
        $('#DataID').val(el.id);
        $('#region').val(region)
       }
   </script>

@endsection
