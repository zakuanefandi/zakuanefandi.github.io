---
title: Adobe Air - Installer file is damaged error
layout: post
date: '2019-06-20 08:00:00 +0800'
author: admin
tags: difference
---

While digging my old projects for the portfolio, I've found the installation disk for my Final Year Project during my collage days.
An application built with Adobe Flash is called UTHMNav which now featured in my project page.
When finished copying it to the local disk, I execute the installation script and encounter this error while installing the application.
<!--more-->

image

What I had first in mind when i saw the error was outdated Air Runtime, so I've downloaded and install the latest version. Unfortunatly the error still persist.

After google-ing the problem on the internet, I found just a simple solution for this from this <a href="https://www.youtube.com/watch?v=fLZwAuF4nis">video</a>.
I just need to change the date of my computer to before 30 October 2017 and re-run the installation script and viola, the application now can install.

This issue is due to failed signature validation of the application package which have been signed with SHA1 certificate.

I assumed the certificate used to sign the the AIR application installation has expired on 30 October 2017.

Adobe official notice on this issue can be found here: http://blogs.adobe.com/flashplayer/2017/11/adobe-air-applications-installation-issue.html