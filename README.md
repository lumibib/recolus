<h1 align="center">Recolus</h1> <br>
<p align="center">
    <img alt="recolus logo" title="recolus logo" src="https://img.icons8.com/color/344/analytics.png" width="200">
</p>
<div align="center">
  <strong>Self-hosted, Laravel web analytics</strong>
</div>
<div align="center">
  Simple and easy web analytics in PHP for those who care about privacy.
</div>

<div align="center">
  <h3>
    <a href="https://github.com/lumibib/recolus#documentation">Documentation</a>
    <span> | </span>
    <a href="https://ravedoni.com/recolus">Demo</a>
    <span> | </span>
    <a href="https://github.com/lumibib/recolus#contributing">
      Contributing
    </a>
  </h3>
</div>

<div align="center">
  <sub>Built with ❤︎ by
  <a href="https://michaelravedoni.ch">Michael Ravedoni</a> and
  <a href="https://github.com/lumibib/recolus/contributors">
    contributors
  </a>
  </sub>
</div>

## 👋 Introduction

[![Conventional Commits](https://img.shields.io/badge/Conventional%20Commits-1.0.0-yellow.svg?style=flat-square)](https://conventionalcommits.org)
[![license](https://img.shields.io/github/license/lumibib/recolus.svg?style=flat-square)](https://github.com/lumibib/recolus/blob/master/LICENSE)

Self-hosted, Laravel PHP based analytics tool for those who care about privacy. Recolus runs on your own server, analyzes the traffic of your websites and provides useful statistics in a minimal interface.

We believe that you don't need to track every aspect of your visitors. Recolus keeps tracked data anonymized to avoid that users are identifiable, while still providing helpful insights. It's the right tool for everyone who doesn't need a full-featured marketing analytics platform like Google Analytics or Matomo.


## Table of Contents

- [Features](#features)
- [Install](#install)
- [Update](#update)
- [Documentation](#documentation)
- [Contributing](#contributing)
- [Changelog](#changelog)
- [Roadmap](#roadmap)
- [Authors and acknowledgment](#authors-and-acknowledgment)
- [Inspirations and packages used](#inspirations-and-packages-used)
- [License](#license)

## Features

- **Self-hosted**: Recolus runs on your own server and is 100% open-source
- **Public dashboard**: Instantly see how many visitors are coming to your website, where they come from and what they do once they're there. 
- **Privacy-aware**: Doesn’t track users and doesn't need a GDPR notice. 
- **No cookies**: No required cookie message. Identify unique visits without cookies or persistently storing any personal data.
- **Useful data**: Keeps useful statistics such as browser information, location, and device. Keep track of referring sites and campaigns.
- **Lightweight script**: Lightweight and fast script; adds just ~2.5 KiB of extra data to your site.
- **Custom variable**: You can filter your analysis with a custom variable set on your sites.

## Install

Follow this instructions to "install" Recolus.

### 1. Download the source code

Clone this repository or [download it](https://github.com/lumibib/recolus/archive/master.zip) as an archive and decompress-it.

**A. Using `git clone`**

```bash
cd /var/www # Or wherever you chose to install web applications to
git clone https://github.com/lumibib/recolus.git
cd recolus
git tag -l

# Examples below
v1.0.0
v1.1.0
v2.0.0
v2.1.0
```

```bash
git checkout v2.1.0
```
> ℹ️ The tags below are examples of what will be shown. You should always run git checkout on the latest tag. Recolus is constantly evolving therefore, we advise you to always download and use the latest tagged release. Using a non-tagged release or the `main` branch may result in an unstable or broken environment.

**B. Download the zip archive**

[Download Recolus](https://github.com/lumibib/recolus/archive/master.zip) as an archive and decompress it where you install web applications on your server.

### 2. Install Composer

If not already installed, [install composer](https://getcomposer.org/download/).

And then install required packages.

```bash
composer update --no-dev -o
```

### 3. Install Recolus

Recolus comes with an installation command that will:

- Configure database
- Configure environment
- Create an admin user
- Run migrations
- Run seeders (for demo if you want)

Follow the instructions running this command in your terminal at the root of your project (`cd PATH_TO_RECOLUS_ROOT`).

```bash
php artisan recolus:install
```

> ℹ️ Getting a 500 - Internal Server Error?
> If you get a 500 error when visiting your status page, you may need to run `chmod -R 777 storage` for it to work or `rm -rf bootstrap/cache/*`. You can also try to give permissions to cache `chmod -R 777 bootstrap/`

### 4. Configure the database

By default Recolus comes with a `.env.example` file. You'll need to rename this file to just `.env` regardless of what environment you're working on.

It's now just a case of editing this new `.env` file and setting the values of your setup.

> You can configure database with `php artisan recolus:install` command or manually.

## Update

If you've installed Recolus using Git, then upgrading should be straight forward, requiring just a few minutes.

> ℹ️ Before you get started, backup!
> Make sure that you've taken a backup of your Recolus database. We test thoroughly, but you never know.

Follow the instructions running this command in your terminal at the root of your project (`cd PATH_TO_RECOLUS_ROOT`).

```bash
php artisan recolus:update
```

If you want, you can update with theses command and then run `php artisan recolus:update` again :

```bash
git fetch origin main
git tag -l
git checkout LATEST_TAG
```
> Checking out a LATEST_TAG value won't work. You need to replace this with the latest version from the output of git tag -l.


## 📚 Documentation

Comming.

## Contributing

We’re really happy to accept contributions from the community, that’s the main reason why we open-sourced it! There are many ways to contribute, even if you’re not a technical person.

1. Fork it (<https://github.com/lumibib/recolus/fork>)
2. Create your feature branch (`git checkout -b feature/fooBar`)
3. Commit your changes (`git commit -am 'Add some fooBar'`)
4. Push to the branch (`git push origin feature/fooBar`)
5. Create a new Pull Request

## Changelog

You will find changelogs in the [CHANGELOG.md](https://github.com/lumibib/recolus/blob/master/CHANGELOG.md) file.

## Roadmap

- Localization
- Check host with the domain_whitelist attribute
- Check paths with the ignore_paths attribute
- Display UTM attributes
- Automated events like downloads, outbound links, and email clicks.
- Custom Domain filter
- Browser and Platform versions in dedicated cards
- Device width card
- Filter by attribute when clicking an item in cards
- Export and API
- Email automate reports
- Dark mode
- Password protected public site page

## Authors and acknowledgment

- **Michael Ravedoni** - *Initial work* - [michaelravedoni](https://github.com/michaelravedoni)

See also the list of [contributors](https://github.com/lumibib/recolus/contributors) who participated in this project.

## Inspirations and packages used

**Inspirations**

- [Simple Analytics](https://www.simpleanalytics.com/)
- [Ackee](https://ackee.electerious.com/)
- [laravel-analytics](https://github.com/andreaselia/laravel-analytics)
- [Goatcounter](https://www.goatcounter.com/)

**Packages an tools**

 - [Tailwind elements](https://tailwind-elements.com/)
 - [Apex charts](https://apexcharts.com/)
 - [laravel-apexcharts](https://github.com/akaunting/laravel-apexcharts)
 - [How to write a Good readme](https://bulldogjob.com/news/449-how-to-write-a-good-readme-for-your-github-project)
 - [Flagpedia](https://flagpedia.net)
 - [Google favicon API](https://www.google.com/s2/favicons)
 - [Browser icons](https://github.com/moneesh2011/browser-icons)

## License

Recolus is open-sourced software licensed under the [MIT License](https://opensource.org/licenses/MIT).
