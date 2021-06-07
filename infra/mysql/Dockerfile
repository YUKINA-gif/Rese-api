FROM mysql/mysql-server:8.0

ENV MYSQL_DATABASE=rese \
  MYSQL_USER=root \
  MYSQL_PASSWORD=dbpj \
  TZ=Asia/Tokyo

COPY ./my.cnf /etc/my.cnf
RUN chmod 644 /etc/my.cnf