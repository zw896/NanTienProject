package digital5.nantien.Models;



public class EventItem {


    private String itemID;
    private String itemName;
    private String description;

    public EventItem(){

    }

    public EventItem(String itemID, String itemName, String description) {
        this.itemID = itemID;
        this.itemName = itemName;
        this.description = description;
    }

    public String getItemID() {
        return itemID;
    }

    public void setItemID(String itemID) {
        this.itemID = itemID;
    }

    public String getItemName() {
        return itemName;
    }

    public void setItemName(String itemName) {
        this.itemName = itemName;
    }

    public String getDescription() {
        return description;
    }

    public void setDescription(String description) {
        this.description = description;
    }

    @Override
    public String toString() {
        return "EventItem{" +
                "itemID='" + itemID + '\'' +
                ", itemName='" + itemName + '\'' +
                ", description='" + description + '\'' +
                '}';
    }


}

