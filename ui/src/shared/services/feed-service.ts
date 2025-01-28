import { decorate, injectable } from "inversify";
import "reflect-metadata";
import { FeedConfigDTO, FeedConfigModel } from "~/src/shared/types";
import { di } from "~/src/shared/lib/di";

export class FeedService {
  private feedRepo: any;
  private feedsDict: FeedConfigDTO;
  private feedsList: FeedConfigModel[];

  constructor() {
    this.feedRepo = di.container.get(di.TYPES.FeedRepository);
    this.feedsDict = {};
    this.feedsList = [];
  }

  public getFeeds(): FeedConfigModel[] {
    return this.feedsList;
  }

  public getFeedsDict(): FeedConfigDTO {
    return this.feedsDict;
  }

  private async fetchData(): Promise<void> {
    this.feedsDict = await this.feedRepo.getData();

    this.feedsList = Object.keys(this.feedsDict).map((key) => {
      return {
        id: key,
        name: key,
        ...this.feedsDict[key],
      };
    });
  }
}

decorate(injectable(), FeedService);
