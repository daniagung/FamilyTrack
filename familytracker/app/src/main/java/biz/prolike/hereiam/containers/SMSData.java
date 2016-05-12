package biz.prolike.hereiam.containers;
import java.io.Serializable;
public class SMSData  implements Serializable {

    String number;
    String body;
    String type;
    String date;

    public SMSData() {

    }

    public SMSData (String number, String body, String type, String date) {
        this.number = number;
        this.body = body;
        this.type = type;
        this.date = date;
    }

    public void setNumber(String number) { this.number = number; }
    public void setBody(String body) { this.body = body; }
    public void setType(String type) { this.type = type; }
    public void setDate(String date) { this.date = date; }

    public String getNumber() {return this.number;}
    public String getBody() {return this.body;}
    public String getType() {return this.type;}
    public String getDate() {return this.date;}
}
