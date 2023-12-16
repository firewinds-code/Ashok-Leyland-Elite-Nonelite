@extends("layouts.masterlayout")
@section('title','General Ticket List')
@section('bodycontent')
	<div class="content-wrapper mobcss">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">General Ticket List</h4>
                <div class="row">
                    <div class="col-md-12">
                        <form name="myForm" method="post" action="{{url('store-general-ticket')}}">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="datefrom" >Date From</label>
                                    <span style="color: red;">*</span>
                                    <input type="text" name="datefrom" id="datefrom2" autocomplete="off" class="form-control" value="@isset($dateFrom){{$dateFrom}} @endisset" />
                                    <span id="datefrom_error" style="color:red"></span> 
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="dateto" >Date To</label>
                                    <span style="color: red;">*</span>
                                    <input type="text" name="dateto" id="dateto2" autocomplete="off" class="form-control" value="@isset($dateTo){{$dateTo}} @endisset" />
                                    <span id="dateto_error" style="color:red"></span> 
                                </div>
                                <div class="form-group col-md-4" style="position: relative;top: 28px">
                                
                                    <input type="submit" name="submit" id="submit" value="Submit" class="btn-secondary">
                                    {{-- <input type="submit" name="submit" id="close" value="Close" class="btn-secondary"> --}}
                                </div>
                            
                            </div>
                        
                        
                        </form>
                    </div>
                    
                </div><br>
                <div class="row">
                    <div class="col-md-12">
                    	
						<div class="table-responsive">
							<table id="order-listing" class="table">
                                <thead>
                                    <tr>
										<th>Caller Name</th>
										<th>Caller Number</th>
										<th>Primary Filter / Disposition</th>
										<th>Secondary Filter / Disposition</th>
										<th>Comments</th>										
										<th>Created By</th>
										<th>Created Date</th>
										<th>Created Time</th>										
                                    </tr>
                                </thead>
                                <tbody>
                                @isset($rowData)
                                @php $count=1; @endphp							
									@foreach($rowData as $row)
                                    <tr>					
										<td class="cls_caller_name">{{$row->caller_name}}</td>
                                        <td class="cls_caller_number">{{$row->caller_number}}</td>
										<td class="cls_disposition">{{$row->prim_disposition}}</td>
										<td class="cls_disposition1">{{$row->sec_disposition}}</td>
										<td class="cls_comments">{{$row->comments}}</td>
										<td class="cls_comments">{{$row->created_by}}</td>
										<td class="cls_comments">{{date('d-m-Y',strtotime($row->created_at))}}</td>
										<td class="cls_comments">{{date('H:i:s',strtotime($row->created_at))}}</td>
										
                                        
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
    $(document).ready(function() {
		$('#datefrom2').val("{{$dateFrom}}").datetimepicker({ maxDate: 0,format:'Y-m-d',timepicker:false});
   		$('#dateto2').val("{{$dateTo}}").datetimepicker({ maxDate: 0,format:'Y-m-d',timepicker:false});
	});
</script>
@endsection
