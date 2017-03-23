class CardCompare {

    static compareCMC(cmc1, cmc2) {
        if (cmc1 > cmc2) {
            return 1;
        } else if (cmc1 < cmc2) {
            return -1;
        }
        return 0;
    }

}
