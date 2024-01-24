export type FeedConfigModel = {
  id: string;
  type: string;
  description: string;
  loader_config: Object;
  categories_show: boolean;
  post_processors: any[];
  not_accepted_categories: any[];
  export_processors: any[];
  extra_processors: any[];
};

export type FeedConfigDTO = {
  [key: string]: {
    type: string;
    description: string;
    loader_config: Object;
    categories_show: boolean;
    post_processors: any[];
    not_accepted_categories: any[];
    export_processors: any[];
    extra_processors: any[];
  };
};
