package biz.prolike.hereiam.containers;

import java.io.Serializable;

public class LocData implements Serializable {

    String lon;
    String lat;
    String date;
    String address;

    public LocData() {

    }

    public LocData(String lon, String lat, String date) {
        this.lon = lon;
        this.lat = lat;
        this.date = date;
    }

    public void setLon(String lon) { this.lon = lon; }
    public void setLat(String lat) { this.lat = lat; }
    public void setDate(String date) { this.date = date; }
    public void setAddress(String address) { this.address = address; }

    public String getLon()  {return this.lon;}
    public String getLat() {return this.lat;}
    public String getDate() {return this.date;}
    public String getAddress() { return this.address; }
}

