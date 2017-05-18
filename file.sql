create table file_info(
  `id` int(5) not null auto_increment PRIMARY key,
  `filename` varchar(200) not null DEFAULT '' comment '文件上传之前的名字',
  `title` varchar(200) not null DEFAULT '' comment '文章标题',
  `file_path` varchar(200) nt null DEFAULT '' comment '文件上传后保存的路径名',
  `content` mediumtext not null DEFAULT '' comment '文件内容',
  `upload_time` int not null DEFAULT 0 comment '文件上传时间'
)engine=innodb default charset=utf8;