FROM nginx:1.23.3-alpine

ADD ./nginx.conf /etc/nginx/nginx.conf
ADD ./conf.d/default.conf /etc/nginx/conf.d/default.conf
ADD ./fastcgi_params.conf /etc/nginx/fastcgi_params.conf

#CMD ["nginx"]
#
#EXPOSE 80 443
