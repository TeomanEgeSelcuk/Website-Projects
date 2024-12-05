#!/usr/bin/env ruby
require 'cgi'
require 'cgi/util'

cgi = CGI.new

# Retrieve form data and capitalize first letters
city = cgi['city'].split.map(&:capitalize).join(' ')
province = cgi['province'].split.map(&:capitalize).join(' ')
country = cgi['country'].split.map(&:capitalize).join(' ')
image_url = cgi['image_url']

print cgi.header("text/html; charset=UTF-8")
print <<EOF
<!DOCTYPE html>
<html>
<head>
    <title>#{CGI.escapeHTML(city)}, #{CGI.escapeHTML(country)}</title>
    <style>
        body {
            text-align: center;
        }
        h1 {
            background-color: #4CAF50;
            color: white;
            font-size: 48px;
            padding: 20px;
        }
        img {
            width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <h1>#{CGI.escapeHTML(city)}, #{CGI.escapeHTML(country)}</h1>
    <img src="#{CGI.escapeHTML(image_url)}" alt="Image of #{CGI.escapeHTML(city)}">
</body>
</html>
EOF
