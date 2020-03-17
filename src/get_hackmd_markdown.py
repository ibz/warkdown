#!/usr/bin/env python3

from bs4 import BeautifulSoup
import requests
import sys

if __name__ == '__main__':
    hackmd_id = sys.argv[1]
    text = requests.get("https://hackmd.io/%s?edit" % hackmd_id).text
    soup = BeautifulSoup(text, 'html.parser')
    md = soup.find('div', class_='markdown-body').text
    print(md)
