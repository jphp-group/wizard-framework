package org.develnext.jphp.browser.fx.ext.classes;

import org.develnext.jphp.browser.fx.ext.BrowserFXExtension;
import org.develnext.jphp.browser.fx.ext.support.Chromium;
import org.develnext.jphp.ext.javafx.classes.UXNode;
import org.develnext.jphp.ext.jcef.classes.PCefBrowser;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Getter;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.reflection.ClassEntity;

@Reflection.Name(BrowserFXExtension.NS + "Chromium")
public class UXChromium extends UXNode<Chromium> {
    public UXChromium(Environment env, Chromium wrappedObject) {
        super(env, wrappedObject);
    }

    public UXChromium(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct(PCefBrowser browser) {
        __wrappedObject = new Chromium(browser.getWrappedObject());
    }

    @Getter
    public PCefBrowser getEngine(Environment env) {
        return new PCefBrowser(env, getWrappedObject().getEngine());
    }
}
