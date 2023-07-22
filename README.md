
<h1 id="v2ray-collector" align="center">V2Hub</h1>
<p align="center">
  <a href="https://t.me/v2raycollectorbot">
    <img src="https://img.shields.io/badge/Telegram_Bot-@v2raycollectorbot-darkblue?style=flat&logo=telegram" alt="Telegram Bot">
  </a>
  <a href="https://scrutinizer-ci.com/g/yebekhe/V2Hub/build-status/main">
    <img src="https://scrutinizer-ci.com/g/yebekhe/V2Hub/badges/build.png?b=main" alt="Build Status">
  </a>
  <a href="https://scrutinizer-ci.com/code-intelligence">
    <img src="https://scrutinizer-ci.com/g/yebekhe/V2Hub/badges/code-intelligence.svg?b=main" alt="Code Intelligence Status">
  </a>
  <a href="https://scrutinizer-ci.com/g/yebekhe/V2Hub/?branch=main">
    <img src="https://img.shields.io/scrutinizer/quality/g/yebekhe/V2Hub?style=flat&logo=scrutinizerci" alt="Scrutinizer Code Quality">
  </a>
</p>
<p align="center">
  <img src="https://img.shields.io/github/languages/top/yebekhe/V2Hub?color=5D6D7E" alt="Github Top Language">
  <img src="https://img.shields.io/github/license/yebekhe/V2Hub?color=5D6D7E" alt="GitHub license">
  <img alt="GitHub Repo stars" src="https://img.shields.io/github/stars/yebekhe/V2Hub">
  <img alt="GitHub commit activity (branch)" src="https://img.shields.io/github/commit-activity/t/yebekhe/V2Hub">
</p>
<p align="center">
  <b>This project is intended for educational purposes only. Any other use of it, including commercial, personal, or non-educational use, is not accepted!</b>
</p>
<p align="center">V2Hub is a script that can be used to aggregate and merge multiple subscription links for V2Ray, Trojan, Shadowsocks, and other protocols. The script can process both plain text and base64-encoded subscription links.</p>
<h2 id="instructions-usage">Instructions &amp; Usage</h2>
<p>Just import the following subscription link into the corresponding client. Use a client that at least support ss + vless + vmess + trojan.</p>
<table>
  <thead>
    <tr>
      <th>CONFIG TYPE</th>
      <th>NORMAL SUBSCRIPTION</th>
      <th>BASE64 SUBSCRIPTION</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>Merged</td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/V2Hub/main/merged">NORMAL SUBSCRIPTION</a>
      </td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/V2Hub/main/merged_base64">BASE64 SUBSCRIPTION</a>
      </td>
    </tr>
    <tr>
      <td>VMESS</td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/V2Hub/main/Split/Normal/vmess">NORMAL SUBSCRIPTION</a>
      </td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/V2Hub/main/Split/Base64/vmess">BASE64 SUBSCRIPTION</a>
      </td>
      </tr>
    <tr>
      <td>VLESS</td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/V2Hub/main/Split/Normal/vless">NORMAL SUBSCRIPTION</a>
      </td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/V2Hub/main/Split/Base64/vless">BASE64 SUBSCRIPTION</a>
      </td>
      </tr>
    <tr>
      <td>REALITY</td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/V2Hub/main/Split/Normal/reality">NORMAL SUBSCRIPTION</a>
      </td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/V2Hub/main/Split/Base64/reality">BASE64 SUBSCRIPTION</a>
      </td>
      </tr>
    <tr>
      <td>TROJAN</td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/V2Hub/main/Split/Normal/trojan">NORMAL SUBSCRIPTION</a>
      </td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/V2Hub/main/Split/Base64/trojan">BASE64 SUBSCRIPTION</a>
      </td>
      </tr>
    <tr>
      <td>ShadowSocks</td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/V2Hub/main/Split/Normal/shadowsocks">NORMAL SUBSCRIPTION</a>
      </td>
      <td>
        <a href="https://raw.githubusercontent.com/yebekhe/V2Hub/main/Split/Base64/shadowsocks">BASE64 SUBSCRIPTION</a>
      </td>
      </tr>
  </tbody>
</table>
<h2 id="manual-subs-conversion">Manual Subs Conversion</h2>
<ul>
  <li>If your client does not support the formats that provided here use below services to convert them to your client format (like surfboard) <blockquote>
      <p>Services for online sub conversion:</p>
    </blockquote>
  </li>
  <li>
    <a href="https://v2rayse.com/en/node-convert">v2rayse</a>
  </li>
  <li>
    <a href="https://sub.v1.mk/">sub-web-modify</a>
  </li>
  <li>
    <p>
      <a href="https://bianyuan.xyz/">bianyuan</a>
    </p>
  </li>
  <li>
    <p>
      <strong>If you don&#39;t like the groups and rules that are already set, you can simply use bianyuan API like this (ONLY FOR BASE64 SUBSCRIPTION)::</strong>
    </p>
    <blockquote>
      <p>don&#39;t use this API for your personal subs! Pls run the subconverter locally ``` <a href="https://pub-api-1.bianyuan.xyz/sub?target=(OutputFormat)&amp;url=(SubUrl)&amp;insert=false">https://pub-api-1.bianyuan.xyz/sub?target=(OutputFormat)&amp;url=(SubUrl)&amp;insert=false</a>
      </p>
    </blockquote>
  </li>
</ul>
<p>For Example: (OutputFormat) = clash (SubUrl) = <a href="https://raw.githubusercontent.com/yebekhe/V2Hub/main/merged_base64">https://raw.githubusercontent.com/yebekhe/V2Hub/main/merged_base64</a>
</p>
<p>
  <a href="https://pub-api-1.bianyuan.xyz/sub?target=clash&url=https://raw.githubusercontent.com/yebekhe/V2Hub/main/merged_base64&insert=false">https://pub-api-1.bianyuan.xyz/sub?target=clash&url=https://raw.githubusercontent.com/yebekhe/V2Hub/main/merged_base64&insert=false</a>
</p>
<p>Now you can use the link above to import the subs into your client ```</p>
<h2 id="license">License</h2>
<p>This project is licensed under the MIT License - see the LICENSE file for details.</p>
