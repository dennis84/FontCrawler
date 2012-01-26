Server Configuration
====================

    server {
      listen 80;
    
      server_name www.fontcrawler.com;
      root /home/dennis/Projects/FontCrawler/web;
    
      rewrite ^/app\.php/?(.*)$ /$1 permanent;
    
      location / {
        index app.php;
        if (-f $request_filename) {
          break;
        }
        rewrite ^(.*)$ /app.php/$1 last;
      }
    
      location ~ ^/(app|app_dev)\.php(/|$) {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_split_path_info ^(.+\.php)(/.*)$;
        include fastcgi_params;
        fastcgi_param  SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param  HTTPS off;
      }
    }
    
    server {
      listen 80;
    
      server_name test.fontcrawler.com;
      root /home/dennis/Projects/FontCrawler/src/FontCrawler/CrawlerBundle/Tests/Fixures;

      location / {
        index index.html;
      }
    }

