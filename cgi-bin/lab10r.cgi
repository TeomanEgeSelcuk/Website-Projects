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
            background-color: #f0f8ff; /* Light blue background for the entire page */
            text-align: center;
            margin: 0;
            padding: 0;
        }
        h1 {
            background-color: #4CAF50; /* Background color for the header */
            color: yellow; /* Text color for the header */
            font-size: 48px;
            padding: 20px;
            margin: 0;
        }
        img {
            width: 100%;
            height: auto;
            display: block; /* Prevents small gaps beneath images */
        }
    </style>
</head>
<body>
    <h1>#{CGI.escapeHTML(city)}, #{CGI.escapeHTML(country)}</h1>
    <img src="#{CGI.escapeHTML(image_url)}" alt="Image of #{CGI.escapeHTML(city)}">
</body>
</html>
EOF
