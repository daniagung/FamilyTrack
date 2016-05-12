package biz.prolike.hereiam.containers;

import java.io.Serializable;

public class CallData implements Serializable{

    private String calltype;
    private String callnumber;
    private String calldatetime;
    private String callduration;
    private String callname;
    private String battery;

    public CallData()
    {

    }

    public CallData(String calltype, String callnumber, String calldatetime, String callduration, String callname)
    {
        this.calldatetime=calldatetime;
        this.callduration=callduration;
        this.callnumber=callnumber;
        this.calltype=calltype;
        this.callname=callname;
    }

    public String getCalltype() {
        return calltype;
    }

    public void setCalltype(String calltype) {
        this.calltype = calltype;
    }

    public String getCallnumber() {
        return callnumber;
    }

    public void setCallnumber(String callnumber) {
        this.callnumber = callnumber;
    }

    public String getCalldatetime() {
        return calldatetime;
    }

    public void setCalldatetime(String calldatetime) {
        this.calldatetime = calldatetime;
    }

    public String getCallduration() {
        return callduration;
    }

    public void setCallduration(String callduration) {
        this.callduration = callduration;
    }

    public String getCallname() {
        return callname;
    }

    public void setCallname(String callname) {
        this.callname = callname;
    }

    public void setBattery(String battery) {this.battery = battery;}
    public String getBattery(){return battery;}


}