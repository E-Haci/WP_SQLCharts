<?php
function gvn_chart_sample_nonxml_data(){
$data['titles']=array(
  'Posts by month',
  'Posts by year',
  'Popular users/publishers',
  );

$data['sql_queries']=array(
  'select count(*) pcount,SUBSTRING(`post_date`,6,2) aysay,SUBSTRING(`post_date`,1,4) ilsay,SUBSTRING(`post_date`,1,7) ayilsay from wp_posts  group by aysay,ilsay  order by aysay ',

  'select count(*) pcount, SUBSTR(`post_date`,1,4) il from wp_posts  group by il order by il asc limit 10 ',

  'select count(*) as pcount,a.post_author,b.display_name as dname
from wp_posts a 
inner join wp_users b ON a.post_author=b.ID
where a.post_status="publish"
group by a.post_author order by pcount desc limit 10',
  );

$data['others']=array(

array('Post count','pcount','Monthes','ayilsay',500,400,'column'),

array('Post count','pcount','Years','il',500,400,'pie'),

array('Post count','pcount','Display name','dname',500,400,'3dpie'),


	);

$existence= get_page_by_title( 'Posts by month', OBJECT, 'gvn_schart' );

if (empty($existence)){
foreach ($data['titles'] as $key => $value) {
	$newid=wp_insert_post(
		array('post_title'    => $value,
			'post_type'=>'gvn_schart',
			'post_status'=>'private'
			)
		);


    add_post_meta($newid,'guaven_sqlcharts_code',$data["sql_queries"][$key]);

	add_post_meta($newid,'guaven_sqlcharts_chartwidth',$data["others"][$key][4]);
	add_post_meta($newid,'guaven_sqlcharts_chartheight',$data["others"][$key][5]);
	add_post_meta($newid,'guaven_sqlcharts_xarg_s',$data["others"][$key][1]);
	add_post_meta($newid,'guaven_sqlcharts_xarg_l',$data["others"][$key][0]);
	add_post_meta($newid,'guaven_sqlcharts_yarg_s',$data["others"][$key][3]);
	add_post_meta($newid,'guaven_sqlcharts_yarg_l',$data["others"][$key][2]);
	add_post_meta($newid,'guaven_sqlcharts_graphtype',$data["others"][$key][6]);
}
}


}
?>