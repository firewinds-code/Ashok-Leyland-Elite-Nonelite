@extends("layouts.masterlayout")
@section('title','Area')
@section('bodycontent')
	<div class="content-wrapper mobcss">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">General Ticket</h4>
                <div class="row">
                    <div class="col-md-12">
                    	<div id="insertvehicle" >
							<form name="myForm" method="post" enctype="multipart/form-data" action="{{url('general-ticket-store')}}">
	                        	<input type="hidden" name="_token" value="{{csrf_token()}}">
	                        	<input  type="hidden" name="dataid" id="dataid"/>                        
	                            <div class="row">
									<div class="form-group col-md-3">
										<label for="caller_name">Caller Name</label> <span style="color: red;">*</span>
										<input type="text" name="caller_name" id="caller_name" class="form-control" required/>
									</div>                                 
	                                <div class="form-group col-md-3">
										<label for="caller_number">Caller Number</label> <span style="color: red;">*</span>
										<input type="tel" name="caller_number" id="caller_number" maxlength="10" class="form-control" required/>
									</div>
									
									<div class="form-group col-md-3">
	                                    <label for="disposition">Primary Filter / Disposition</label>                                    
	                                    <select name="disposition" id="disposition" class="form-control" onchange="primDisposition(this.value)" required>
											<option Value="">--select--</option>
											@foreach ($general_prim_dispostion as $row)
												<option value="{{ $row->id }}">{{ $row->disposition }}</option>
											@endforeach
										</select>									
	                                </div> 
	                                <div class="form-group col-md-3">
	                                    <label for="disposition1">Secondary Filter / Disposition</label>                                    
	                                    <select name="disposition1" id="disposition1" class="form-control" required>
											<option Value="">--select--</option>										
										</select>								
	                                </div>
									<div class="form-group col-md-6">
										<label for="comments">Comments</label> <span style="color: red;">*</span>
										<textarea name="comments" id="comments" class="form-control" cols="30" rows="5" required></textarea>
										
									</div>										                                                        
	                            </div>
	                            <div class="box-footer">
	                                <span class="pull-right">
									<button type="button" onclick="reloadPage();" class="btn-secondary">Cancel</button>	
	                                <input type="submit"name="submit" id="submit" value="Submit" class="btn-secondary">									
	                                </span>
	                            </div>
	                        </form>  
						</div> 
						                    
                        <div class="clear"></div>
                       
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
	function primDisposition(param){
		$.ajax({
			url:'{{url("prim-disposition")}}',
			data:{'id':param},
			success:function(data){
				console.log(data);
				var result = data.split('##');
				var str = '';
				result.pop();
				for(item in result){
					result2 = result[item].split('~~');
					str += "<option value ='" +result2[0]+ "'>"+ result2[1] +"</option>";
				}
				document.getElementById('disposition1').innerHTML = str;
			}
		});
	}
</script>
@endsection
