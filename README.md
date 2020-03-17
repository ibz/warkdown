MediaWiki extension for including Markdown or HackMD documents into a page.

This extension will add the `<markdown>` tag to MediaWiki.

Example 1 (inline markdown)
===

```
<markdown>
Hello, **markdown**!

Things to do in the morning:
* brush teeth
* drink water
* make coffee

Please don't use any `<blink>` tags!
</markdown>
```

This will render the markdown inside the MediaWiki page.

Example 2 (pass the ID of a HackMD document)
===

```
<markdown src="features"></markdown>
```

This requires a CodiMD and a [splash](https://github.com/scrapinghub/splash) instance to be run in Docker containers. We pull the markdown from HackMD and render it using CodiMD.
