# EASY QUEUE 项目
这是一个用redis的list为核心的php项目，主要是提供多进程的功能，给和我一样对于java多线程项目不了解的phper，并且我很想大家可以参与进来和我一起来维护，本项目使用的框架也是模仿thinkphp的模式开发的，针对html部分不是很友好

# 如何启动项目（和TP很类似）
1、和所有的web项目一样，部署（nginx/apache），将项目指向public目录，即可访问，例如eq.com

2、第一次访问的时候会提示，要输入账户用户名和密码

3、进入后台之后，右上角会有“新增队列/注销账号”两个功能，新增队列的时候，按照要求输入数据即可，当redis连接不上的话，会有对应的提示

4、项目目录到/public下php server.php start或者php server.php stop，开启或者关闭进程【此步骤之前必须执行完1、2、3】

# 如何查看进程情况
1、后台新增的监听可以查看

后台如下：
![后台截图](https://github.com/github-Leoxie/easy_queue/blob/master/doc/jt.png)


2、可以通过 ps -ef | grep EASY 查看

结果如下：

root@e2af0a1a2996:/mnt/www/project/easy_queue/public#ps -ef | grep EASY

root     33328     0  3 16:05 pts/1    00:00:00 EASY-QUEUE-MASTER

root     33329 33328  0 16:05 pts/1    00:00:00 EASY-QUEUE-CHILDabfff33a1ef3d6d96685a63f80329b1d-33329

root     33330 33328  0 16:05 pts/1    00:00:00 EASY-QUEUE-CHILDabfff33a1ef3d6d96685a63f80329b1d-33330

root     33331 33328  0 16:05 pts/1    00:00:00 EASY-QUEUE-CHILDabfff33a1ef3d6d96685a63f80329b1d-33331

root     33334 33328  0 16:05 pts/1    00:00:00 EASY-QUEUE-CHILDabfff33a1ef3d6d96685a63f80329b1d-33334

root     33336 33328  0 16:05 pts/1    00:00:00 EASY-QUEUE-CHILDabfff33a1ef3d6d96685a63f80329b1d-33336

root     33338 33328  0 16:05 pts/1    00:00:00 EASY-QUEUE-CHILDabfff33a1ef3d6d96685a63f80329b1d-33338

# 希望
1、希望和我一样对于java多线程不是很了解，或者是为了帮助我们这群人的技术大牛能够，一起帮我维护这个项目

2、作为自己的“孩子”，我非常希望他可以茁壮成长。

# 联系方式

邮箱：811329263@qq.com

QQ：811329263
