#!/usr/bin/env python3

from bs4 import BeautifulSoup
import os
import requests
import sys
import yaml

if __name__ == '__main__':
    config = yaml.safe_load(open(os.path.join(sys.path[0], 'config.yaml')))

    hackmd_id = sys.argv[1]
    r = requests.get("https://hackmd.io/%s?edit" % hackmd_id)
    hack_soup = BeautifulSoup(r.text, 'html.parser')
    md = hack_soup.find('div', class_='markdown-body').text

    r = requests.post("http://%(HOST_IP)s:%(CODIMD_PORT)s/new" % config,
                      headers={"Content-type": "text/markdown"},
                      data=md.encode('utf-8'))

    codimd_id = r.url.split('/')[-1]

    r = requests.get("http://%(HOST_IP)s:%(SPLASH_PORT)s/render.html?wait=1&url=http://%(HOST_IP)s:%(CODIMD_PORT)s/%(codimd_id)s?view" % dict(config, codimd_id=codimd_id))
    codi_soup = BeautifulSoup(r.text, 'html.parser')
    html = codi_soup.find('div', id='doc').decode_contents()

    print(html)
