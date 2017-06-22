# Titan

Useful classes used for every Laravel project
--under development--

Want to see the current package in action, have a look at my starter project.
[Laravel Starter Project](https://github.com/bpocallaghan/laravel-admin-starter)

#### Documentation is in progress

# What it includes

### Generate unique Slug

## Models

##### LogsActivities.php
Model to get all the activities in the database. 

##### TitanCMSModel.php
Model that use the LogsActivity, ModifyBy traits and have the messages variable. (The base admin model, to get the modify by and activities per model)

##### TitanWebsiteNavigation.php
Base Website Navigation. Title-Url Attribute helper, Gets the parent, generate the complete url (based on parent)

##### TitanAdminNavigation.php
Base Admin Navigation. Helper functions to generate complete url, get all his parents.

## Model Traits

#### ActiveTrait.php
Add the 'active_from, active_to'. Example: You have banners on your website and would to set the active period. This will by default set it active forever, or you can specify the from and to dates, and then you can Model::active()

#### ImageThumb.php
If your model has an 'image' column and you have a thumb image, this trait will assume your thumb is the same name just with a '-tn' extension, So it saves you a image_thumb column and to create that 'attribute' function on your model.

##### LogsActivity.php
When a model triggers the created, updated, deleted event it will log an activity with the before and after values.

##### ModifyBy.php
On creating, updating, deleting. It will set the current logged in user id.

##### RecursiveParent.php
Gives you the recursive parent functionality on the same model. Your table has a 'parent_id' column. Simple example will be categories, then you do not have to create a 'sub_categories' table and can just add a 'parent_id' column.

##### SlugUnique.php
Generate a unique slug. It will suffix "-index" if slug is already in use

##### SlugUniqueModels.php
Create a list of models. This will create a unique slug from all those models.

##### TaggedImages.php
Lets assume you have an images table and images_tab table. Then you can 'tag,link' image to a model, instead of reuploading the same image, so you can tag 1 image to multiple models, For example you have a 'gallery' and you can tag those images to a news article, or to a player or to a banner, etc.

##### TaggedNews.php
For news, blogs websites. Example: You have a sport news website. You can then tag news articles to a player, team, venue. So when you visit the players page, you can easily find all his tagged news articles.

##### URLFromCategory.php
Generate a complete url from yourself and category_id slug. Example: Your categories have the 'parent_id' and you want to generate the complete url. Then you can find the category, entry on the full url and do not need to explode on the slugs and find it.

##### URLFromParent
Generate a complete url from yourself and parent's slug. Example: Your categories have the 'parent_id' and you want to generate the complete url. Then you can find the category, entry on the full url and do not need to explode on the slugs and find it.

## Installation

```bash
composer require bpocallaghan/titan
```

## TODO

- Documentation (50%)
- Cleanup code
- Rename, more descriptive names for the classes

## Note

Please keep in mind this is for my personal workflow and might not fit your need.
I developed this to help speed up my day to day workflow. 
Please let me know about any issues or if you have any suggestions.

## My other Packages

- [Flash a bootstrap alert](https://github.com/bpocallaghan/alert)
- [Laravel Notifications with icons and animations and with a timeout](https://github.com/bpocallaghan/notify)
- [Laravel custom Generate Files with a config file and publishable stubs](https://github.com/bpocallaghan/generators)
