@extends("layouts.masterlayout")
@section('title','PSF Import File')
@section('bodycontent')
	<div class="content-wrapper mobcss">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">PSF Import File</h4>
                <div class="row">
                    <div class="col-md-12">
                        <form method="post" action="{{ url('psf/uploadfile') }}" enctype="multipart/form-data">
                        @csrf
                            <div class="form-group col-md-8">
			                        <label for="datefrom" >Select CSV File <b style="font-size:10px;">(Max. 1.3 MB)</b></label>
									<span style="color: red;">*</span>
                                    <input type="file" name="import_file"   id="file"  class="form-control" required/>
			                </div><br>
                            <div class="form-group col-md-4">
                                <input type="submit" name="upload"  class="btn-primary" value="Upload" />
                                <span style="text-decoration: underline;"><a href="{{ asset('psf_format.csv') }}" title="Download Format">Download Format</a></span>
                            </div>
                        </form>
                    </div><br>
                   {{-- <div class="form-group col-md-4">
                        <span><a href="{{ asset('psf_format.csv') }}">Download Format</a></span>
                    </div>  --}}
               </div>
           </div>
      </div>
 </div>
@endsection
