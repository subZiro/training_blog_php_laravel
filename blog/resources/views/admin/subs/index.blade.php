@extends('admin.layout')

@section('content')
<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
	<!-- Content Header (Page header) -->
		<section class="content-header">
  			<h1>Подписчики</h1>
  			<ol class="breadcrumb">
    			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    			<li><a href="#">Examples</a></li>
				<li class="active">Blank page</li>
			</ol>
		</section>
		<!-- Main content -->
		<section class="content">
  		<!-- Default box -->
  			<div class="box">
        		<!-- /.box-header -->
        		<div class="box-body">
          			<table id="example1" class="table table-bordered table-striped">
	            		<thead>
	            			<tr>
								<th>ID</th>
								<th>Email</th>
								<th>Действия</th>
	            			</tr>
	            		</thead>
            			<tbody>
            				@foreach($subs as $sub)
				            <tr>
			              		<td>{{$sub->id}}</td>
			              		<td>{{$sub->email}}</td>
				              	<td>
				              		{{ Form::open(['route'=>['subs.destroy', $sub->id], 'method'=>'delete']) }}
	                  					<button type="submit" class="delete" onclick="return confirm('Удалить подписку?)">
		                  					<i class="fa fa-remove"></i>
                    					</button>
                    				{{ Form::close() }}
                    			</td>
				            </tr>
				            @endforeach
            			</tbody>
          			</table>
        		</div>
        		<!-- /.box-body -->
      		</div>
			<!-- /.box -->
		</section>
	<!-- /.content -->
	</div>
<!-- /.content-wrapper -->


@endsection