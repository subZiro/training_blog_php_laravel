@extends('admin.layout')

@section('content')

<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
	<!-- Content Header (Page header) -->
		<section class="content-header">
			<h1>Blank page</h1>
		</section>

		<!-- Main content -->
		<section class="content">
		<!-- Default box -->
			<div class="box">
				<div class="box-header">
					<h3 class="box-title">Листинг сущности</h3>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					<table id="example1" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>ID</th>
								<th>Текст</th>
								<th>Пост</th>
								<th>Пользователь</th>
								<th>Действия</th>
							</tr>
						</thead>
						<tbody>
							@foreach($comments as $comment)
								<tr>
									<td>{{$comment->id}}</td>
									<td>{{$comment->text}}</td>
									<td>{{$comment->post_id}}</td>
									<td>{{$comment->user_id}}</td>
									<td>{{$comment->text}}</td>
									<td>
										@if($comment->status == 1)
											<a href="/admin/comments/toggle/{{$comment->id}}" class="fa fa-lock"></a>
										@else
											<a href="/admin/comments/toggle/{{$comment->id}}" class="fa fa-thumbs-o-up"></a>
										@endif
										{{Form::open(['route'=>['comments.destroy', $comment->id], 'method'=>'delete'])}}
											<button onclick="return confirm('Удалить комментарий?')" type="submit" class="delete">
												<i class="fa fa-remove"></i>
											</button>
										{{Form::close()}}
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