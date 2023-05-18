<?php

//$this->add_hook('welcome','hello');
function hello()
{
	echo "Hello word!";
}

//$this->add_hook('admin_menu','hello_menu');

function hello_menu($arg)
{
	$active = NULL;
	if($arg['request']->params['controller'] == 'plugins')	 $active = 'class="active"';
	echo
	"<li  ".$active.">
		<a href='".__SITE_URL."admin/plugins/index'>
			<i class='fa fa-group'></i>
			<span>".__d('hello','bahar_group')."</span>
		</a>
	</li>";
}

?>
