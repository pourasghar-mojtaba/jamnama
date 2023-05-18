<?php

$this->add_hook('admin_menu','project_menu');

function project_menu($arg)
{
	$active = NULL;
	if($arg['request']->params['controller'] == 'projects')	 $active = 'class="active"';
	echo
	"<li  ".$active.">
	<a href='".__SITE_URL."admin/project/projects/index'>
	<i class='fa fa-gg'></i>
	<span>".__d(__PROJECT_LOCALE,'project_managment')."</span>
	</a>
	</li>";
}

$this->add_hook('last_project','last_project_slider');
function last_project_slider($arg){
	
	App::uses('ProjectAppModel', 'Project.Model');
	App::uses('Project', 'Project.Model');
	$project = new Project();
	$project->recursive = - 1;	 
	$options['fields'] = array(
		'Project.id',
		'Project.title',
		'(select image from projectimages where project_id = Project.id limit 0,1)as image'
	);
	$options['conditions'] = array(
		'Project.status'=> 1
	);
	$options['order'] = array(
		'Project.id'=>'desc'
	);
	$options['limit'] = 5;
	$projects = $project->find('all',$options);
	if(!empty($projects)){
		echo "<ul class='bxslider'>";
		foreach($projects as $project){
			echo "
				<li>
					<em>";
						echo $arg['Html']->image(__SITE_URL.__PROJECT_IMAGE_URL.$project['0']['image'],array('alt'=>$project['Project']['title']));
						echo" 
						<a href='".__SITE_URL.__PROJECT_IMAGE_URL.$project['0']['image']."' class='fancybox-button' title='".$project['Project']['title']."' data-rel='fancybox-button'>
							<i class='fa fa-search icon-hover icon-hover-2'>
							</i>
						</a>
					</em>
					<a class='bxslider-block' href='#'>
						<strong>
							".$project['Project']['title']."
						</strong>
					</a>
				</li>
			";
		}
		echo "</ul>";
	}
}

$this->add_hook('user_menu','project_user_menu');
function project_user_menu($arg){		
	echo "
		<li class='dropdown'>
            <a class='dropdown-toggle' href='".__SITE_URL."project/projects/index/'>
                ".__d(__PROJECT,'projects')."
                <i class=''></i>
            </a>
        </li>
	";	 
}

?>
