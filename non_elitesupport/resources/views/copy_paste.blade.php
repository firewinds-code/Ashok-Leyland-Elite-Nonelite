@extends("layouts.masterlayout")
@section('title','Copy Paste')
@section('bodycontent')
	<div class="content-wrapper">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Copy Paste</h4>
                <div class="row">
                    <div class="col-md-12">
						<form name="myForm" method="post" enctype="multipart/form-data" action="{{url('store-copy-paste')}}">
                        	<input type="hidden" name="_token" value="{{csrf_token()}}">
                            <div class="row">
                                    <label for="content">Content</label>
                                    <textarea name="content" id="content" cols="80" rows="10" class="form-control"></textarea>
                            </div><br>
                            <div class="box-footer">
                                <span class="pull-right">								
                                    <input type="submit"name="submit" id="submit" value="Submit" class="btn-secondary">
                                </span>
                            </div>
                        </form> 
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table">
                            <tr>
                                <th>content</th>
                            </tr>
                            @isset($contentData)
                                @foreach ($contentData as $row)
                                    <tr>
                                        <td>
                                            {{$row->content}}
                                        </td>
                                    </tr>
                                    
                                @endforeach
                                
                            @endisset
                            <tr>

                            </tr>

                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
  
@endsection
