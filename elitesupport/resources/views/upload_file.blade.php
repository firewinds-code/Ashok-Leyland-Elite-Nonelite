@extends("layouts.masterlayout") 
 @section('title','Copy Paste')
 @section('bodycontent')
 	<div class="content-wrapper">
         <div class="card">
             <div class="card-body">
                 <h4 class="card-title">Copy Paste</h4>
                 <div class="row">
                     <div class="col-md-12">
 						<form name="myForm" method="post" enctype="multipart/form-data" action="{{url('store-upload-file')}}">
                         	<input type="hidden" name="_token" value="{{csrf_token()}}">
                             <div class="row">
                                 <label for="attachment" >Attachment</label>
                                 <input type="file" name="attachment" id="attachment" class="form-control" autocomplete="off"/>
                                 <span id="attachment_error" style="color:red"></span> 
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
                                            <a href='{{asset("public/file_upload/$row->file_name")}}' download="">{{$row->file_name}}</a>
                                            {{-- <a href="{{public_path('file_upload/'.$row->file_name)}}" download="">{{$row->file_name}}</a> --}}
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