@extends("layouts.masterlayout")
@section('title','Region')
@section('bodycontent')
	<div class="content-wrapper">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Manage Region</h4>
                <div class="row">
                    <div class="col-md-12">
                    	
						<form name="myForm" method="post" enctype="multipart/form-data" action="{{url('store-state')}}">
                        	<input type="hidden" name="_token" value="{{csrf_token()}}">
                            <input type="hidden" name="DataID" id="DataID">
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="region">Zone</label> <span style="color: red;">*</span>
                                    <select name="region_id" id="region_id" class="form-control" required>
                                        <option value="">--Select--</option>
                                        @isset($regionData)
                                            @foreach ($regionData as $item)
                                                <option value="{{$item->id}}">{{$item->region}}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="state">Region</label> <span style="color: red;">*</span> 
                                    <input type="text" name="state" id="state" class="form-control" placeholder="Region" required>
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
										<th>Region</th>
										<th style="display: none">region id</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @isset($rowData)
                                @php $count=1; @endphp
									@foreach($rowData as $row)
                                    <tr>
										<td>
											<i class="fa fa-pencil-square-o" aria-hidden="true" id="{{$row->id}}" data-position="left" data-tooltip="Edit" onclick="javascript:return editState(this);" style="cursor: pointer;"></i>
											<a href="{{route('state_delete.stateDelete', ['id' => $row->id])}}" onclick="return confirm('Do you want to delete?')">
												<i class="fa fa-trash-o" aria-hidden="true" style="cursor: pointer;"></i></a>
										</td>
                                        <td>{{$row->region}}</td>
                                        <td class="cls_state">{{$row->state}}</td>
                                        <td class="cls_region_id" style="display: none">{{$row->region_id}}</td>
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
       function editState(el){
        var state = $(el).parents('td').parents('tr').find('.cls_state').text();
        var region_id = $(el).parents('td').parents('tr').find('.cls_region_id').text();
        $('#region_id').val(region_id);
        $('#state').val(state);
        $('#DataID').val(el.id);
       }
   </script>

@endsection
