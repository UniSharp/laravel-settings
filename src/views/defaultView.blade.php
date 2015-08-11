<html>
<head>
	<title>Setting Test Page</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
</head>
<body>
	<div class="container">
		<h1>Setting List</h1>
		<hr>
		<form class="form-inline">
		  <div class="form-group">
		    <label>Key</label>
		    <input type="text" class="form-control" name="key" id="key" placeholder="eg.title, color.black">
		  </div>
		  <div class="form-group">
		    <label>Locale</label>
		    <input type="text" class="form-control" name="lang" id="lang" placeholder="type your language">
		  </div>
		  <a id="search" class="btn btn-default"><span class="glyphicon glyphicon-search"></span> Search</a>
		  <a href="{{url('setting-test/save')}}" class="btn btn-primary pull-right">
		  	<span class="glyphicon glyphicon-floppy-disk"></span> Make sample data
		  </a>
		</form>
		<div id="notify"></div>
		<hr>
		@foreach($settings->chunk(3) as $row)
		  <div class="row">
		  	@foreach($row as $setting)
			  <div class="col-sm-6 col-md-4">
			    <div class="thumbnail">
			      <div class="caption">
				    <form class="form-horizontal">
				      <div class="form-group">
					    <h3><label class="col-xs-9">Key : {{$setting->key}}</label></h3>
					    <div class="col-xs-3">
					      <a href="{{url('setting-test/drop?key='.$setting->key.'&lang='.$setting->locale)}}" class="btn btn-danger pull-right" role="button" onclick="return confirm('Are you sure to delete {{$setting->key}}?')"><span class="glyphicon glyphicon-trash"></span></a>
					    </div>
				      </div>
				    </form>
					<p>Locale : {{$setting->locale}}</p>
				    <div class="well">{{$setting->value}}</div>
			      </div>
			    </div>
			  </div>
			@endforeach
		  </div>
		@endforeach
	</div>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>

	<script>
		$('#search').click( function () {
			var data = { key: $('#key').val(), lang: $('#lang').val() };

			var done = function (data) {
					$('#notify').html('<div class="alert alert-info">'+data+'</div>');
				};

			$.get('setting-test/search', data, done);
		});
	</script>
</body>
</html>