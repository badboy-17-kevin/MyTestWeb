# 我的网站项目

## 部署步骤

1. 确保电脑已安装 Docker Desktop 并启动
2. 克隆本仓库
3. 在项目根目录执行：`docker-compose up -d`
4. 访问 `http://localhost:9527/`

## 导入数据库

如果有数据库备份文件 `backup.sql`，执行：
```bash
docker cp backup.sql myweb-docker-db-1:/backup.sql
docker exec -it myweb-docker-db-1 bash -c "mysql -u root -p123456 mycms < /backup.sql"
