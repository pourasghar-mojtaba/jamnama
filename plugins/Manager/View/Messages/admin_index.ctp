<?php

$items = array();
$controller = 'messages';
$items['action_name'] = __('message');
$items['action'] = 'Message';
$items['add_style'] =
array('link'=>array(
		'title'=>__d('manager','add_managermessage'),
		'url'  => __SITE_URL.'admin/manager/'.$controller.'/add'
	)
);
$items['titles'] = array(
	array('title'=> __d('manager','managermessage'),'index'=> 'message'),
	array('title'=> __d('manager','user'),'index'=> 'name','action'=>'User'),
	array('title'=> __('status'),'index'=> 'status'),
	array('title'=> __('created'),'index'=> 'created'),
);

$records = $messages;
$items['show_search_box'] = FALSE;
echo $this->element('Admin/index_header', array('items'=>$items) );
if(!empty($records)){

	foreach($records as $record){
		echo "
		<tr>
		<td>
		<input type='checkbox' >
		</td>
		";
		echo "<td>".$record[$items['action']]['message'];
		echo $this->AdminHtml->createActionLink();
		echo $this->AdminHtml->actionLink(__('edit'),__SITE_URL.'admin/manager/'.$controller.'/edit/'.$record[$items['action']]['id'],'','|');
		echo $this->AdminHtml->actionLink(__('delete'),__SITE_URL.'admin/manager/'.$controller.'/delete/'.$record[$items['action']]['id'],'delete');
		echo $this->AdminHtml->endActionLink();
		echo"</td>";
		echo "<td>".$record['User']['name']."</td>";
		echo "<td>";
		if($record[$items['action']]['status'] == 0)
		{
			echo $this->AdminHtml->status(__('inactive'),array('class'=>'label label-danger'));
		}
		else echo $this->AdminHtml->status(__('active'),array('class'=>'label label-success'));
		echo"</td>";
		echo "<td>".$this->Cms->show_persian_date(" l ، j F Y    ",strtotime($record[$items['action']]['created']))."</td>";

	}
}
echo $this->element('Admin/index_footer' );
?>

