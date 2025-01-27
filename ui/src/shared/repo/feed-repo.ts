import { decorate, injectable } from "inversify";
import "reflect-metadata";
import axios from "axios";

import { FeedConfigDTO } from "~/src/shared/types";

export class FeedRepository {
  public async getData(): Promise<FeedConfigDTO> {
    const respone = await axios.get("https://site.ru");

    return respone.data as FeedConfigDTO;
  }
}

decorate(injectable(), FeedRepository);
