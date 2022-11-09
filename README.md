# SmurfWorks / Sieve

Quickly and easily browse the data of your Laravel application using app-layer logic.

**THIS REPOSITORY IS A WORK IN PROGRESS AND IN ACTIVE DEVELOPMENT**

- Author:  [Glyn Simpson](https://www.smurfworks.com) / [@SmurfWorks](https://www.twitter.com/smurfworks)
- [Change Log](./CHANGELOG.md)
- [License](./LICENSE.md)

## Current objective

Often application development with Laravel forgoes schema documentation (or annotation) due to the schema-less model maintenance paradigm's employed in the framework's standard development process.

As such, there's often no connection between the schema as it exists and the models that represent it's individual records, beyond what what the application developers add manually to documentation. In fast paced environments such as startups where applications grow faster than they can be offered maintenance windows, being able to reverse engineer data management tools out of a model map can be extremely useful for business analysts trying to understand, integrate and advocate software within a business.

Using the open-sourced [SmurfWorks/Model-Finder](https://www.github.com/SmurfWorks/Model-Finder) package, Sieve aims to provide:

- a convenient way to quickly browse data from a visual web interface query builder, without any inherent understanding of a projects data model;
- a plug-and-play implementation for any modern Laravel project, with no custom setup required for minimal access; *
- a scalable result interface with considerations for sensitive data display, including easy spreadsheet generation;
- a fragmented code structure, allowing you to use the logic with or without the application wrapper included, or place this application contents within your own customised wrapper;
- an independently packaged frontend that will not impact your own if you choose to use the application wrapper; and
- a completely optional documentation layer added inline to your models (Through the model-finder package's attribute parsing)

_* Due to the nature of application development, class parsing and model writing, it's quite possible that your models may have their own namespace paradigm, or may have attributes, scopes or relations that cannot be available to Sieve without additional setup._

## About

The model finder package provides us an index of models in your given namespaces, with their attributes, scopes and relations. Sieve allows us to present these details within a form context, allowing you to visually build a query using the available context and not knowing anything before getting started. The query structure generated is specific to Sieve, but is parsed in the backend to an Eloquent query, which can then write the database query language you're working with. In a way, this is a fluent interface for a fluent interface. 🙃

### So, when would I use this?

Let's say you need a list of users from an application that meet a certain criteria. You're not a developer and/or don't have access or the skills required to work with the application database. The admin area doesn't have filtering criteria that you need or doesn't have direct export functionality. By exposing application-layer rules such as attribute definitions, scopes and relations as a generated form, we allow this persona of user to start querying the data they need, or allow a developer to quickly go get that list of users for the user requesting it.

Your data-risk appetite allows you to configure who has access to this tool and in what environments, but the tool exists to do the job quickly with little knowledge needed. Making it the perfect tool for exploring how the application manages it's data. 

The result is that the better your application is made (using meaningful scopes and relations) the better this tool can be for assisting with administrative tasks without amplifying (and even in some cases de-escalating) your need to maintain and provide documentation.

## Install

```bash
composer require smurfworks/sieve
```

Laravel should automatically discover the service provider, which will register routes, views and configuration. If you would like to use the prebuilt application wrapper distributed with this pacakge, you can run the following command to publish the latest assets:

```bash
php artisan vendor:publish --tag=public
```

At this point, you may need to clear your route cache:

```bash
php artisan route:clear
```

## Usage

By default, the routes for the application will be prefixed with /sieve/ (which can be customised in the configuration!). You can simply access that route and start building your application. 

If you'd like to utilize the query builder as an API, then take a look at the included tests to see the recursive query payload structure. 

## Testing

The testing of this package is largely dependent on the testing of the smurfworks/model-finder package. Why double up on test effort where possible, right?

```bash
./vendor/bin/phpunit
```

## Contributions

Contributions and issue reporting are welcome but this project is an open-source demonstration of how to the model finder package can be used in useful ways. I intend to release paid packages using the model finder, that integrate with Sieve - please consider that you're contributing to a software ecosystem that may personally benefit the original author.

# Additional notes

This project has been multiple things to me personally, including upskilling in technologies such as Tailwind 3, Vite, Vue3, Laravel 9 package development, service providing, testing and progressive delivery of a plug-and-play application. It's not perfect in many ways, so I invite criticism to help me learn. **Happy coding!**
