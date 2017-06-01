create table user(
  `id` int(5) not null auto_increment PRIMARY key,
  `username` varchar(200) not null DEFAULT '' comment '用户名',
  `password` varchar(200) not null DEFAULT '' comment '用户密码',
  `create_time` int not null DEFAULT 0 comment '创建时间'
)engine=innodb default charset=utf8;